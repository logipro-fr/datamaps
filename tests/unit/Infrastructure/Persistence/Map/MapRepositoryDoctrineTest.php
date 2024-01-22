<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Map;

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
}
