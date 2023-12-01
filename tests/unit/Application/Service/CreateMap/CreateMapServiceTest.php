<?php

namespace Datamaps\Tests\Application\Service\CreateMap;

use Datamaps\Application\Presenter\PresenterObject;
use Datamaps\Application\Service\CreateMap\CreateMapRequest;
use Datamaps\Application\Service\CreateMap\CreateMapService;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapFactory;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Tests\Application\Service\AbstractServiceTestCase;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;

use function Safe\json_encode;

class CreateMapServiceTest extends AbstractServiceTestCase
{
    protected function fillRepository(): void
    {
    }

    protected function createService(): void
    {
        $this->service = new CreateMapService($this->repository, new PresenterObject());
    }

    public function testCreateMapResponseToRequest(): void
    {
        $expectedMap = MapBuilder::aMap()->withId(new MapId("test_create_service"))->build();

        $this->service->execute(new CreateMapRequest($this->toJson($expectedMap)));
        $this->checkMapIsCreated($expectedMap);

        $response = $this->getDataFromSuccessfulResponse();
        $this->assertEquals("test_create_service", $response->mapId);
        $this->assertEquals("/api/v1/display/test_create_service", $response->displayUrl);
    }

    private function toJson(Map $map): string
    {
        return json_encode(MapFactory::createObjectFromMap($map));
    }

    private function checkMapIsCreated(Map $expectedMap): void
    {
        $map = $this->repository->findById($expectedMap->getMapId());
        $this->assertTrue($expectedMap->equals($map));
    }
}
