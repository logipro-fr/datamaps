<?php

namespace Datamaps\Application\Service\SearchMaps;

use Datamaps\Application\Service\RequestInterface;

class SearchMapsRequest implements RequestInterface
{
    public function __construct(
        public readonly int $count = 1
    ) {
    }
}
