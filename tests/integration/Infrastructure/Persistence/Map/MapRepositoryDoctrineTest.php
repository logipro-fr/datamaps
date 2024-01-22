<?php

namespace Datamaps\Tests\Integration\Infrastructure\Persistence\Map;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapFactory;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryDoctrine;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use PHPUnit\Framework\TestCase;

class MapRepositoryDoctrineTest extends TestCase
{
    use DoctrineRepositoryTesterTrait;

    private MapRepositoryInterface $mapRepository;

    public function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["maps"]);
        $this->mapRepository = new MapRepositoryDoctrine($this->getEntityManager());
    }

    public function testAddBigMapOf400Markers(): void
    {
        $bigMapExpected = $this->addMapToRepository("map400.json");
        $this->getEntityManager()->flush();
        $this->getEntityManager()->detach($bigMapExpected);

        $bigMap = $this->mapRepository->findById(new MapId("map400"));

        $this->assertTrue($bigMapExpected->getMapId()->equals($bigMap->getMapId()));
        $this->assertEquals($bigMapExpected->getBounds(), $bigMap->getBounds());
        $this->assertEquals($bigMapExpected->getLayers(), $bigMap->getLayers());
        $this->assertEquals($bigMapExpected->getCreationDate(), $bigMap->getCreationDate());
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
