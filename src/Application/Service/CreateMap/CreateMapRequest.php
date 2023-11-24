<?php

namespace Datamaps\Application\Service\CreateMap;

use Datamaps\Application\Service\RequestInterface;

class CreateMapRequest implements RequestInterface
{
    public function __construct(
        public readonly string $json
    ) {
    }
}
