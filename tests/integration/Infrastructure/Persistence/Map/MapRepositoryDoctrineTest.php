<?php

namespace Datamaps\Tests\Integration\Infrastructure\Persistence\Map;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapFactory;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Infrastructure\Persistence\Doctrine\EntityManagerSingleton;
use Datamaps\Tests\Infrastructure\Persistence\Map\MapRepositoryTestBase;
use Datamaps\Tests\Infrastructure\Shared\Symfony\RunCommand;

class MapRepositoryDoctrineTest extends MapRepositoryTestBase
{
    use RunCommand;

    protected function initialize(): void
    {
        $this->runCommand('doctrine:database:drop --force');
        $this->runCommand('doctrine:database:create');

        EntityManagerSingleton::instance()->resetEntityManager();
        $this->mapRepository = new MapRepositoryDoctrineFake();
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
