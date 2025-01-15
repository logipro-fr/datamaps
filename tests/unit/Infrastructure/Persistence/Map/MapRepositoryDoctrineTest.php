<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Map;

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
        $mapRepository->flush();
        $this->assertTrue(true);
    }
}
