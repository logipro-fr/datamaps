<?php

namespace Datamaps\Tests\Application\Service\SearchMaps;

use Datamaps\Application\Presenter\PresenterObject;
use Datamaps\Application\Service\SearchMaps\SearchMapsRequest;
use Datamaps\Application\Service\SearchMaps\SearchMapsService;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Tests\Application\Service\AbstractServiceTestCase;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;

class SearchMapsServiceTest extends AbstractServiceTestCase
{
    protected function fillRepository(): void
    {
        $this->repository->add(MapBuilder::aMap()->withId(new MapId("first"))->build());
        $this->repository->add(MapBuilder::aMap()->withId(new MapId("second"))->build());
        $this->repository->add(MapBuilder::aMap()->withId(new MapId("third"))->build());
    }

    protected function createService(): void
    {
        $this->service = new SearchMapsService($this->repository, new PresenterObject());
    }

    public function testSearchMapsResponse(): void
    {
        $this->service->execute(new SearchMapsRequest());

        $maps = $this->getDataFromSuccessfulResponse()->maps;
        $this->assertCount(1, $maps);
        $this->assertEquals("third", $maps[0]->mapId);
    }

    public function testSearchMapsResponseLimited(): void
    {
        $this->service->execute(new SearchMapsRequest(2));

        $maps = $this->getDataFromSuccessfulResponse()->maps;
        $this->assertCount(2, $maps);
        $this->assertEquals("third", $maps[0]->mapId);
        $this->assertEquals("second", $maps[1]->mapId);
    }

    public function testSearchMapsLimitedInLimitedRepo(): void
    {
        $this->service->execute(new SearchMapsRequest(5));

        $maps = $this->getDataFromSuccessfulResponse()->maps;
        $this->assertCount(3, $maps);
        $this->assertEquals("third", $maps[0]->mapId);
        $this->assertEquals("second", $maps[1]->mapId);
        $this->assertEquals("first", $maps[2]->mapId);
    }
}
