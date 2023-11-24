<?php

namespace Datamaps\Domain\Model\Map\Traits;

use Datamaps\Domain\Model\Map\Exceptions\EmptyRepositoryException;
use Datamaps\Domain\Model\Map\Map;

trait SearchMapsTrait
{
    /**
     * @return array<Map>
     */
    public function getMapsInOrder(int $count = 1): array
    {
        $maps = $this->getMapsSorted($count);

        if ($maps == false) {
            throw new EmptyRepositoryException(
                "Can't retrieve data from an empty repository",
                EmptyRepositoryException::ERROR_CODE
            );
        }

        return $maps;
    }

    /**
     * @return array<Map>|false
     */
    abstract protected function getMapsSorted(int $count): array|false;
}
