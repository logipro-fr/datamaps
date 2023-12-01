<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Map;

use Datamaps\Infrastructure\Persistence\Doctrine\EntityManagerSingleton;

class MapRepositoryDoctrineTest extends MapRepositoryTestBase
{
    protected function initialize(): void
    {
        EntityManagerSingleton::instance('sqlite:///:memory:?cache=shared')->resetEntityManager();
        $this->mapRepository = new MapRepositoryDoctrineFake();
    }
}
