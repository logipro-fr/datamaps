<?php

namespace Datamaps\Domain\Model\Map\Traits;

use Datamaps\Domain\Model\Map\Exceptions\MapNotFoundException;
use Datamaps\Domain\Model\Map\Exceptions\MapAlreadyExistsException;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;

trait AddMapTrait
{
    public function add(Map $map): void
    {
        if ($this->mapDoesNotExist($map->getMapId())) {
            $this->addMapToRepository($map);
        } else {
            throw new MapAlreadyExistsException(
                "Can't create map: map already exists",
                MapAlreadyExistsException::ERROR_CODE
            );
        }
    }

    private function mapDoesNotExist(MapId $mapId): bool
    {
        try {
            $this->findById($mapId);
            return false;
        } catch (MapNotFoundException $e) {
            return true;
        }
    }

    abstract public function findById(MapId $mapId): Map;
    abstract protected function addMapToRepository(Map $map): void;
}
