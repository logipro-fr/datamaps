<?php

namespace Datamaps\Infrastructure\Persistence\Doctrine;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Infrastructure\Persistence\Doctrine\Types\LayersType;
use Datamaps\Infrastructure\Persistence\Doctrine\Types\MapIdType;
use Datamaps\Infrastructure\Persistence\Doctrine\Types\RectangleType;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class DoctrineFacade
{
    /** @var array<string,class-string<Type>> */
    private array $typesToIds;
    /** @var array<string,string> */
    private array $tablesToEntities;

    public function __construct()
    {
        $this->typesToIds = [
            MapIdType::TYPE_NAME => MapIdType::class,
            RectangleType::TYPE_NAME => RectangleType::class,
            LayersType::TYPE_NAME => LayersType::class
        ];
        $this->tablesToEntities = [
            'maps' => Map::class,
        ];
    }

    public function getEntityManager(): EntityManager
    {
        return EntityManagerSingleton::instance()->getEntityManager();
    }

    public function createDatabaseIfNeeded(): void
    {
        $this->addTypes();
        $this->addTablesIfNeeded();
    }

    private function addTypes(): void
    {
        $em = $this->getEntityManager();
        foreach ($this->typesToIds as $name => $className) {
            if (!Type::hasType($name)) {
                Type::addType(
                    $name,
                    $className
                );
            }
        }
    }

    private function addTablesIfNeeded(): void
    {
        $em = $this->getEntityManager();
        $schemaManager = $em->getConnection()->createSchemaManager();
        $tool = new SchemaTool($em);
        foreach ($this->tablesToEntities as $table => $entity) {
            if (!$schemaManager->tablesExist(array($table))) {
                $tool->createSchema([
                    $em->getClassMetadata($entity)
                ]);
            }
        }
    }
}
