<?php

namespace Datamaps\Tests\Domain\Model\Map\Traits;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\Traits\FindMapTrait;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;

class FindMapTraitClass
{
    use FindMapTrait;

    public function __construct(
        private bool $mapCanBeFound = true
    ) {
    }

    protected function findMapById(MapId $mapId): Map|false
    {
        if ($this->mapCanBeFound) {
            return MapBuilder::aMap()->withId($mapId)->build();
        } else {
            return false;
        }
    }
}
