<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Map;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryDoctrine;

class FlushingMapRepositoryDoctrine extends MapRepositoryDoctrine
{
    protected function addMapToRepository(Map $map): void
    {
        parent::addMapToRepository($map);
        parent::flush();
    }
}
