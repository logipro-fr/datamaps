<?php

namespace Datamaps\Tests\Application\Service\DisplayMap;

use Datamaps\Application\Presenter\PresenterObject;
use Datamaps\Application\Service\DisplayMap\MapRequest;
use Datamaps\Application\Service\DisplayMap\MapService;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Tests\Application\Service\AbstractServiceTestCase;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;

class MapServiceTest extends AbstractServiceTestCase
{
    protected function fillRepository(): void
    {
        $this->repository->add(MapBuilder::aMap()->withId(new MapId("test_map_service"))->build());
    }

    protected function createService(): void
    {
        $this->service = new MapService($this->repository, new PresenterObject());
    }

    public function testMapResponseToRequest(): void
    {
        $this->service->execute(new MapRequest("test_map_service"));

        $map = $this->getDataFromSuccessfulResponse();
        $this->assertEquals("test_map_service", $map->mapId);
    }
}
