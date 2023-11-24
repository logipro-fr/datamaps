<?php

namespace Datamaps\Tests\Integration\Infrastructure\Persistence\Map;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryDoctrine;

class MapRepositoryDoctrineFake extends MapRepositoryDoctrine
{
    protected function addMapToRepository(Map $map): void
    {
        parent::addMapToRepository($map);
        $this->getEntityManager()->flush();
    }
}
