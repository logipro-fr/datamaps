<?php

namespace Datamaps\Tests\Integration\Infrastructure\Persistence\Map;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapFactory;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Tests\Infrastructure\Persistence\Map\MapRepositoryTestBase;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;

class MapRepositoryDoctrineTest extends MapRepositoryTestBase
{
    use DoctrineRepositoryTesterTrait;

    protected function initialize(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["maps"]);
        $this->mapRepository = new MapRepositoryDoctrineFake($this->getEntityManager());
    }

    public function testAddBigMapOf400Markers(): void
    {
        $bigMapExpected = $this->addMapToRepository("map400.json");
        $bigMap = $this->mapRepository->findById(new MapId("map400"));
        $this->assertEquals($bigMapExpected, $bigMap);
    }

    private function addMapToRepository(string $filename): Map
    {
        $mapRawData = file_get_contents("./tests/resources/" . $filename);
        $this->assertIsString($mapRawData);
        $mapObj = json_decode($mapRawData);
        $this->assertInstanceOf(\stdClass::class, $mapObj);
        $map = MapFactory::createMapFromObject($mapObj);
        $this->mapRepository->add($map);
        return $map;
    }
}
