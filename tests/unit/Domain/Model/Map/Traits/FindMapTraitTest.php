<?php

namespace Datamaps\Tests\Domain\Model\Map\Traits;

use Datamaps\Domain\Model\Map\Exceptions\MapNotFoundException;
use Datamaps\Domain\Model\Map\MapId;
use PHPUnit\Framework\TestCase;

class FindMapTraitTest extends TestCase
{
    public function testFindMapTrait(): void
    {
        $trait = new FindMapTraitClass();
        $map = $trait->findById(new MapId("My_findmaptrait_map"));
        $this->assertTrue((new MapId("My_findmaptrait_map"))->equals($map->getMapId()));
    }

    public function testCantFindMapTrait(): void
    {
        $this->expectException(MapNotFoundException::class);

        $trait = new FindMapTraitClass(false);
        $map = $trait->findById(new MapId("My_findmaptrait_map"));
    }
}
