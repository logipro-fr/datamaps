<?php

namespace Datamaps\Infrastructure\Persistence\Map;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Domain\Model\Map\Traits\AddMapTrait;
use Datamaps\Domain\Model\Map\Traits\FindMapTrait;
use Datamaps\Domain\Model\Map\Traits\SearchMapsTrait;

class MapRepositoryInMemory implements MapRepositoryInterface
{
    use AddMapTrait;
    use FindMapTrait;
    use SearchMapsTrait;

    private const MAP_IS_YOUNGER = 1;
    private const MAP_IS_OLDER = -1;

    /** @var array<int,Map> */
    private array $maps = [];

    private function addMapToRepository(Map $map): void
    {
        $this->maps[] = $map;
    }

    private function findMapById(MapId $mapId): Map|false
    {
        foreach ($this->maps as $map) {
            if ($map->getMapId()->equals($mapId)) {
                return $map;
            }
        }
        return false;
    }

    /**
     * @return array<Map>|false
     */
    private function getMapsSorted(int $count): array|false
    {
        if (sizeof($this->maps) < 1) {
            return false;
        }
        $searchMaps = $this->maps;
        usort($searchMaps, array($this, 'sortMapsByCreationDate'));

        return array_slice($searchMaps, 0, $count);
    }

    private function sortMapsByCreationDate(Map $map, Map $other): int
    {
        $diff = $map->getCreatedAt()->diff($other->getCreatedAt())->invert;
        if ($diff == 0) {
            return self::MAP_IS_YOUNGER;
        } else {
            return self::MAP_IS_OLDER;
        }
    }
}
