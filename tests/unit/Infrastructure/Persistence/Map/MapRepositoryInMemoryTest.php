<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Map;

use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;

class MapRepositoryInMemoryTest extends MapRepositoryTestBase
{
    protected function initialize(): void
    {
        $this->mapRepository = new MapRepositoryInMemory();
    }
}
