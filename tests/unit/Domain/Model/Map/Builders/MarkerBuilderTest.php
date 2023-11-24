<?php

namespace Datamaps\Tests\Domain\Model\Map\Builders;

use Datamaps\Domain\Model\Map\Color;
use Datamaps\Domain\Model\Map\Marker;
use Datamaps\Domain\Model\Map\Point;
use PHPUnit\Framework\TestCase;

class MarkerBuilderTest extends TestCase
{
    public function testBuildMarker(): void
    {
        $marker = MarkerBuilder::aMarker()->build();
        $this->assertInstanceOf(Marker::class, $marker);
        $this->assertEquals(new Point(0, 0), $marker->getPoint());
        $this->assertEquals("default marker description", $marker->getDescription());
        $this->assertEquals(Color::BLUE, $marker->getColor());
    }

    public function testBuildMarkerWithPoint(): void
    {
        $marker = MarkerBuilder::aMarker()->withPoint(new Point(1, 1))->build();
        $this->assertEquals(new Point(1, 1), $marker->getPoint());
    }

    public function testBuildMarkerWithDescription(): void
    {
        $marker = MarkerBuilder::aMarker()->withDescription("My special marker")->build();
        $this->assertEquals("My special marker", $marker->getDescription());
    }

    public function testBuildMarkerWithColor(): void
    {
        $marker = MarkerBuilder::aMarker()->withColor(Color::RED)->build();
        $this->assertEquals(Color::RED, $marker->getColor());
    }
}
