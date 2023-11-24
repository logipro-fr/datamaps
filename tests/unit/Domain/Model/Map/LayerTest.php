<?php

namespace Datamaps\Tests\Domain\Model\Map;

use Datamaps\Domain\Model\Map\Color;
use Datamaps\Domain\Model\Map\Layer;
use Datamaps\Domain\Model\Map\Marker;
use Datamaps\Domain\Model\Map\Point;
use PHPUnit\Framework\TestCase;

class LayerTest extends TestCase
{
    public function testCreateLayer(): void
    {
        $markers = [
            new Marker(new Point(48.86, 2.35), "Paris, capital of France", Color::RED),
            new Marker(new Point(45.73, 4.84), "Lyon, second biggest city of France", Color::GREEN)
        ];
        $layer = new Layer("Cities", $markers);

        $this->assertEquals("Cities", $layer->getName());
        $this->assertEquals($markers, $layer->getMarkers());
    }
}
