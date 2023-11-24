<?php

namespace Datamaps\Application\Service\DisplayMap;

use Datamaps\Application\Service\RequestInterface;

class MapRequest implements RequestInterface
{
    public function __construct(
        public readonly string $id
    ) {
    }
}
