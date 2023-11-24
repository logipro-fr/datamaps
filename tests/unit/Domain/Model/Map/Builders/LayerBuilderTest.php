<?php

namespace Datamaps\Tests\Domain\Model\Map\Builders;

use Datamaps\Domain\Model\Map\Color;
use Datamaps\Domain\Model\Map\Layer;
use Datamaps\Domain\Model\Map\Marker;
use Datamaps\Domain\Model\Map\Point;
use PHPUnit\Framework\TestCase;

class LayerBuilderTest extends TestCase
{
    public function testBuildLayer(): void
    {
        $layer = LayerBuilder::aLayer()->build();
        $this->assertInstanceOf(Layer::class, $layer);
        $this->assertEquals("default layer name", $layer->getName());
        $this->assertEquals([], $layer->getMarkers());
    }

    public function testBuildLayerNamed(): void
    {
        $layer = LayerBuilder::aLayer()->withName("my personal layer")->build();
        $this->assertEquals("my personal layer", $layer->getName());
    }

    public function testBuildLayerWithMarkers(): void
    {
        $markers = [
            new Marker(new Point(0, 0), "marker n1", Color::BLUE),
            new Marker(new Point(1, 1), "marker n2", Color::RED),
        ];
        $layer = LayerBuilder::aLayer()->withMarkers($markers)->build();
        $this->assertEquals($markers, $layer->getMarkers());
    }
}
