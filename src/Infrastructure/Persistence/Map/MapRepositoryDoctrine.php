<?php

namespace Datamaps\Infrastructure\Persistence\Map;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Domain\Model\Map\Traits\AddMapTrait;
use Datamaps\Domain\Model\Map\Traits\FindMapTrait;
use Datamaps\Domain\Model\Map\Traits\SearchMapsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @extends EntityRepository<Map>
 */
class MapRepositoryDoctrine extends EntityRepository implements MapRepositoryInterface
{
    use AddMapTrait;
    use FindMapTrait;
    use SearchMapsTrait;

    public function __construct(EntityManagerInterface $em)
    {
        /** @var ClassMetadata<Map> */
        $class = $em->getClassMetadata(Map::class);
        parent::__construct($em, $class);
    }

    protected function addMapToRepository(Map $map): void
    {
        $this->getEntityManager()->persist($map);
    }

    private function findMapById(MapId $mapId): Map|false
    {
        $map = $this->getEntityManager()->find(Map::class, $mapId);
        if ($map === null) {
            return false;
        }
        return $map;
    }

    /**
     * @return array<Map>|false
     */
    private function getMapsSorted(int $count): array|false
    {
        /** @var array<Map> */
        $searchMaps = $this->getEntityManager()
            ->createQueryBuilder()
            ->select("m")
            ->from(Map::class, "m")
            ->orderBy("m.createdAt", "DESC")
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();

        if (sizeof($searchMaps) < 1) {
            return false;
        }

        return $searchMaps;
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
