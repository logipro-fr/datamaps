<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Map;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\Point;
use Datamaps\Domain\Model\Map\Rectangle;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryDoctrine;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;

class MapRepositoryDoctrineTest extends MapRepositoryInMemoryTest
{
    use DoctrineRepositoryTesterTrait;

    protected function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["maps"]);
        $this->mapRepository = new FlushingMapRepositoryDoctrine($this->getEntityManager());
    }

    public function testFlush(): void
    {
        $this->initDoctrineTester();
        $mapRepository = new MapRepositoryDoctrine($this->getEntityManager());
        $map = new Map(new Rectangle(new Point(1, 1), new Point(3, 3)));
        $mapRepository->add($map);
        $mapRepository->flush();
        $this->assertNotFalse($mapRepository->find($map->getMapId()));
    }
}
