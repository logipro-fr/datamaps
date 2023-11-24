<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Doctrine;

use Datamaps\Infrastructure\Persistence\Doctrine\EntityManagerSingleton;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class EntityManagerSingletonTest extends TestCase
{
    protected string $databaseUrl;

    protected function setUp(): void
    {
        $this->databaseUrl = 'sqlite:///var/sqlite-unit-test.db';
    }

    public function testGetEntityManagerSingleton(): void
    {
        $ems = EntityManagerSingleton::instance($this->databaseUrl);
        $this->assertInstanceOf(EntityManagerSingleton::class, $ems);
    }

    public function testGetEntityManager(): void
    {
        $em = EntityManagerSingleton::instance($this->databaseUrl)->getEntityManager();
        $this->assertInstanceOf(EntityManager::class, $em);
    }

    public function testResetEntityManager(): void
    {
        $ems = EntityManagerSingleton::instance($this->databaseUrl);
        $ems->getEntityManager();
        $ems->resetEntityManager();
        $this->assertEntityManagerHasBeenUnsetted($ems);
    }

    private function assertEntityManagerHasBeenUnsetted(EntityManagerSingleton $entityManagerSingleton): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessageMatches(
            '/\$em must not be accessed before initialization/'
        );
        $this->accessEntityManagerValue($entityManagerSingleton);
    }

    private function accessEntityManagerValue(EntityManagerSingleton $entityManagerSingleton): void
    {
        $emsReflection = new ReflectionClass(EntityManagerSingleton::class);
        $entityManagerValue = $emsReflection->getProperty("em")->getValue($entityManagerSingleton);
    }

    public function testGetDatabaseUrlFromENV(): void
    {
        if (isset($_ENV['DATABASE_URL'])) {
            $d_env = $_ENV['DATABASE_URL'];
        } else {
            $d_env = null;
        }

        $_ENV['DATABASE_URL'] = getenv('DATABASE_URL');
        $this->assertTrue(isset($_ENV['DATABASE_URL']));

        $em = EntityManagerSingleton::instance()->getEntityManager();
        $this->assertInstanceOf(EntityManager::class, $em);

        if ($d_env == null) {
            unset($_ENV['DATABASE_URL']);
        } else {
            $_ENV['DATABASE_URL'] = $d_env;
        }
    }
}
