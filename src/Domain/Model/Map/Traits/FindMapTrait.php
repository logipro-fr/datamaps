<?php

namespace Datamaps\Domain\Model\Map\Traits;

use Datamaps\Domain\Model\Map\Exceptions\MapNotFoundException;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;

trait FindMapTrait
{
    public function findById(MapId $mapId): Map
    {
        $map = $this->findMapById($mapId);
        if ($map == false) {
            throw new MapNotFoundException(
                sprintf("Map with mapId '%s' not found.", $mapId),
                MapNotFoundException::ERROR_CODE
            );
        }
        return $map;
    }

    abstract protected function findMapById(MapId $mapId): Map|false;
}
