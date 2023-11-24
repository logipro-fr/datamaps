<?php

namespace Datamaps\Domain\Model\Map;

use Datamaps\Domain\Model\Map\Exceptions\EmptyRepositoryException;
use Datamaps\Domain\Model\Map\Exceptions\MapAlreadyExistsException;
use Datamaps\Domain\Model\Map\Exceptions\MapNotFoundException;

interface MapRepositoryInterface
{
    /**
     * @throws MapAlreadyExistsException
     */
    public function add(Map $map): void;

    /**
     * @throws MapNotFoundException
     */
    public function findById(MapId $mapId): Map;

    /**
     * @throws EmptyRepositoryException
     * @return array<Map>
     */
    public function getMapsInOrder(int $count = 1): array;
}
