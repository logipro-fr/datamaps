<?php

namespace Datamaps\Tests\Domain\Model\Map\Builders;

use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\Point;
use Datamaps\Domain\Model\Map\Rectangle;
use PHPUnit\Framework\TestCase;

class MapBuilderTest extends TestCase
{
    public function testBuildMap(): void
    {
        $map = MapBuilder::aMap()->build();
        $this->assertInstanceOf(Map::class, $map);
        $this->assertEquals(new Rectangle(new Point(0, 0), new Point(1, 1)), $map->getBounds());
    }

    public function testBuildMyMapBounded(): void
    {
        $rect = new Rectangle(new Point(-1, -2), new Point(10, 11));

        $map = MapBuilder::aMap()->withBoundsAs($rect)->build();
        $this->assertEquals($rect, $map->getBounds());
    }

    public function testBuildMyMapIdentified(): void
    {
        $map = MapBuilder::aMap()->withId(new MapId("my_map"))->build();
        $this->assertTrue((new MapId("my_map"))->equals($map->getMapId()));
    }

    public function testBuildMyMapWithLayer(): void
    {
        $layer1 = LayerBuilder::aLayer()->withName("first")->build();
        $layer2 = LayerBuilder::aLayer()->withName("second")->build();

        $map = MapBuilder::aMap()->withLayer($layer1)->withLayer($layer2)->build();
        $this->assertEquals($layer1, $map->getLayers()[0]);
        $this->assertEquals($layer2, $map->getLayers()[1]);
    }
}
