<?php

namespace Datamaps\Tests\Domain\Model\Map;

use Datamaps\Domain\Model\Map\MapId;
use PHPUnit\Framework\TestCase;

class MapIdTest extends TestCase
{
    public function testCreateMapId(): void
    {
        $mapId = new MapId();
        $this->assertIsString($mapId->getId());
        $this->assertStringStartsWith(MapId::PREFIX_NAME, $mapId->getId());
        $this->assertTrue(strlen(MapId::PREFIX_NAME) < strlen($mapId->getId()));
        $this->assertTrue(strlen($mapId) <= MapId::MAX_SIZE);
    }

    public function testDifferentiateMapIds(): void
    {
        $mapId1 = new MapId("1");
        $mapId2 = new MapId("2");
        $this->assertFalse($mapId1->equals($mapId2));
    }

    public function testSameIds(): void
    {
        $mapId1 = new MapId("1");
        $mapId2 = new MapId("1");
        $this->assertTrue($mapId1->equals($mapId2));
    }
}
