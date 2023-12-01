<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Doctrine;

use Datamaps\Infrastructure\Persistence\Doctrine\DoctrineFacade;
use Datamaps\Infrastructure\Persistence\Doctrine\EntityManagerSingleton;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class DoctrineFacadeTest extends TestCase
{
    protected function setUp(): void
    {
        EntityManagerSingleton::instance('sqlite:///:memory:?cache=shared')->resetEntityManager();
    }

    public function testGetEntityManager(): void
    {
        $doctrineFacade = new DoctrineFacade();

        $em = $doctrineFacade->getEntityManager();

        $this->assertInstanceOf(EntityManager::class, $doctrineFacade->getEntityManager());
    }

    public function testCreateDatabase(): void
    {
        $doctrineFacade = new DoctrineFacade();

        $em = $doctrineFacade->getEntityManager();

        $this->assertEmpty($em->getConnection()->createSchemaManager()->listTables());

        $doctrineFacade->createDatabaseIfNeeded();

        $this->assertNotEmpty($em->getConnection()->createSchemaManager()->listTables());
    }
}
