<?php

namespace Datamaps\Tests\Domain\Model\Map\Traits;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\Traits\SearchMapsTrait;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;

class SearchMapsTraitClass
{
    use SearchMapsTrait;

    public function __construct(
        private bool $repositoryIsEmpty = false
    ) {
    }

    /**
     * @return array<Map>|false
     */
    protected function getMapsSorted(int $count): array|false
    {
        if ($this->repositoryIsEmpty) {
            return false;
        }

        $maps = array();
        for ($i = 0; $i < $count; $i++) {
            $maps[] = MapBuilder::aMap()->build();
        }
        return $maps;
    }
}
