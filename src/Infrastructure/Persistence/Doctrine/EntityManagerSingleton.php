<?php

namespace Datamaps\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\ORMSetup;
use Exception;

class EntityManagerSingleton
{
    private const PREFIX = 'Datamaps\Domain\Model';

    private const DATABASE_URL = 'DATABASE_URL';

    private const IS_DEV_MODE = true;

    private EntityManager $em;
    private static ?EntityManagerSingleton $ems = null;

    private function __construct(private string $databaseUrl)
    {
    }

    public static function instance(?string $databaseUrl = null): self
    {
        if ($databaseUrl === null) {
            $databaseUrl = self::getDatabaseUrlWithEnv();
        }
        if (self::$ems == null) {
            self::$ems = new self($databaseUrl);
        }
        return self::$ems;
    }

    public static function getDatabaseUrlWithEnv(): string
    {
        if (isset($_ENV[self::DATABASE_URL])) {
            return $_ENV[self::DATABASE_URL];
        }
        if (is_string(getenv(self::DATABASE_URL))) {
            return getenv(self::DATABASE_URL);
        }
        return "";
    }

    public function getEntityManager(): EntityManager
    {
        if (!isset($this->em)) {
            $this->em = $this->createEntityManager();
        }
        return $this->em;
    }

    private function createEntityManager(): EntityManager
    {

        $config = $this->createConfigWithXmlDriver();


        $connection = DriverManager::getConnection([
            'url' => $this->databaseUrl
        ], $config);

        return new EntityManager($connection, $config);
    }

    private function createConfigWithXmlDriver(): Configuration
    {
        $driver = $this->createSimplifiedXmlDriver();
        $config = ORMSetup::createConfiguration(self::IS_DEV_MODE);

        $config->setMetadataDriverImpl($driver);

        return $config;
    }

    private function createSimplifiedXmlDriver(): SimplifiedXmlDriver
    {
        $doctrinePath = new DoctrineMappingPath();
        $driver = new SimplifiedXmlDriver(
            [
                $doctrinePath->getFullPath() => self::PREFIX,
            ],
            isXsdValidationEnabled: false
        );
        return $driver;
    }

    public function resetEntityManager(): void
    {
        unset($this->em);
    }
}
