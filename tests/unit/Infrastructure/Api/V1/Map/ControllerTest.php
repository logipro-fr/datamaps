<?php

namespace Datamaps\Tests\Infrastructure\Api\V1\Map;

use Datamaps\Application\Presenter\PresenterObject;
use Datamaps\Application\Service\CreateMap\CreateMapRequest;
use Datamaps\Application\Service\CreateMap\CreateMapService;
use Datamaps\Application\Service\DisplayMap\MapRequest;
use Datamaps\Application\Service\DisplayMap\MapService;
use Datamaps\Application\Service\SearchMaps\SearchMapsRequest;
use Datamaps\Application\Service\SearchMaps\SearchMapsService;
use Datamaps\Domain\Model\Map\Exceptions\EmptyRepositoryException;
use Datamaps\Domain\Model\Map\Exceptions\MapAlreadyExistsException;
use Datamaps\Domain\Model\Map\Exceptions\MapNotFoundException;
use Datamaps\Domain\Model\Map\MapFactory;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function Safe\json_encode;

class ControllerTest extends TestCase
{
    public function testGetMap(): void
    {
        $repository = new MapRepositoryInMemory();
        $map = MapBuilder::aMap()->withId(new MapId("get_map"))->build();
        $repository->add($map);

        /** @var MockObject */
        $service = $this->createMock(MapService::class);
        $service->expects($this->once())->method("execute");
        $request = new MapRequest("get_map");

        /** @var MapService $service */
        $controller = new Controller($service);
        $controller->execute($request);
    }

    public function testMapNotFoundException(): void
    {
        $repository = new MapRepositoryInMemory();
        $presenter = new PresenterObject();

        $service = new MapService($repository, $presenter);

        $controller = new Controller($service);
        $controller->execute(new MapRequest("map_does_not_exist"));

        $response = $controller->readResponse();
        $this->assertInstanceOf(\stdClass::class, $response);

        $this->assertFalse($response->success);
        $this->assertEquals(MapNotFoundException::ERROR_CODE, $response->error_code);
        $this->assertEquals("Map with mapId 'map_does_not_exist' not found.", $response->message);
    }

    public function testMapAlreadyExistsException(): void
    {
        $mapRepository = new MapRepositoryInMemory();
        $map = MapBuilder::aMap()->withId(new MapId("existing_map"))->build();
        $mapRepository->add($map);

        $service = new CreateMapService($mapRepository, new PresenterObject());
        $controller = new Controller($service);
        $controller->execute(new CreateMapRequest(json_encode(MapFactory::createObjectFromMap($map))));

        $response = $controller->readResponse();
        $this->assertInstanceOf(\stdClass::class, $response);

        $this->assertFalse($response->success);
        $this->assertEquals(MapAlreadyExistsException::ERROR_CODE, $response->error_code);
        $this->assertEquals("Can't create map: map already exists", $response->message);
    }

    public function testEmptyRepositoryException(): void
    {
        $service = new SearchMapsService(new MapRepositoryInMemory(), new PresenterObject());
        $controller = new Controller($service);
        $controller->execute(new SearchMapsRequest());

        $response = $controller->readResponse();
        $this->assertInstanceOf(\stdClass::class, $response);

        $this->assertFalse($response->success);
        $this->assertEquals(EmptyRepositoryException::ERROR_CODE, $response->error_code);
        $this->assertEquals("Can't retrieve data from an empty repository", $response->message);
    }
}
