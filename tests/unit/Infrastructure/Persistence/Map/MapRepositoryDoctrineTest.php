<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Map;

use Datamaps\Infrastructure\Persistence\Doctrine\EntityManagerSingleton;

class MapRepositoryDoctrineTest extends MapRepositoryTestBase
{
    protected function initialize(): void
    {
        $this->deleteSqliteDatabaseFile();
        EntityManagerSingleton::instance('sqlite:///var/sqlite-unit-test.db')->resetEntityManager();
        $this->mapRepository = new MapRepositoryDoctrineFake();
    }

    private function deleteSqliteDatabaseFile(): void
    {
        $filename = getcwd() . '/var/sqlite-unit-test.db';
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
}
