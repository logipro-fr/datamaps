<?php

namespace Datamaps\Domain\Model\Map\Event;

use Phariscope\Event\EventAbstract;

class MapCreated extends EventAbstract
{
    public function __construct(
        public readonly string $mapId
    ) {
    }
}
