<?php

namespace Datamaps\Tests\Domain\Model\Map\Traits;

use Datamaps\Domain\Model\Map\Exceptions\MapNotFoundException;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\Traits\AddMapTrait;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;

class AddMapTraitClass
{
    use AddMapTrait;

    public bool $mapCreated = false;

    public function __construct(
        private bool $mapDoesNotExist = true
    ) {
    }

    protected function findById(MapId $mapId): Map
    {
        if ($this->mapDoesNotExist) {
            throw new MapNotFoundException("", 0);
        }
        return MapBuilder::aMap()->withId($mapId)->build();
    }

    protected function addMapToRepository(Map $map): void
    {
        $this->mapCreated = true;
    }
}
