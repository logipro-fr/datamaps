<?php

namespace Datamaps\Tests\Infrastructure\Api\V1\Symfony;

use Datamaps\Application\Presenter\PresenterJson;
use Datamaps\Application\Service\CreateMap\CreateMapService;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Api\V1\Symfony\CreateMapControllerSymfony;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryDoctrine;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Generator\MockClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function Safe\json_encode;

class CreateMapControllerSymfonyTest extends TestCase
{
    private EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        /** @var MockObject $entityManager */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method("flush");
        /** @var EntityManagerInterface $entityManager */
        $this->entityManager = $entityManager;
    }

    public function testExecute(): void
    {
        $repository = new MapRepositoryInMemory();

        $controller = new CreateMapControllerSymfony($repository, $this->entityManager);
        $request = Request::create(
            "/api/v1/create",
            "POST",
            content: json_encode([
                "mapId" => "myMapId",
                "bounds" => [[1,2],[3,4]]
            ])
        );
        $response = $controller->createMap($request);
        $this->assertNotFalse($response->getContent());

        /** @var \stdClass $responseObject */
        $responseObject = json_decode($response->getContent());

        $this->assertTrue($responseObject->success);
        $this->assertEquals("myMapId", $responseObject->data->mapId);

        $mapInRepo = $repository->findById(new MapId("myMapId"));
        $this->assertInstanceOf(Map::class, $mapInRepo);
    }

    public function testExecuteError(): void
    {
        $repository = new MapRepositoryInMemory();

        $controller = new CreateMapControllerSymfony($repository, $this->entityManager);
        $request = Request::create(
            "/api/v1/create",
            "POST",
            content: ""
        );
        $response = $controller->createMap($request);
        $this->assertNotFalse($response->getContent());

        /** @var \stdClass $responseObject */
        $responseObject = json_decode($response->getContent());

        $this->assertFalse($responseObject->success);
    }
}
