<?php

namespace Datamaps\Tests\Domain\Model\Map;

use Datamaps\Domain\Model\Map\Color;
use Datamaps\Domain\Model\Map\Marker;
use Datamaps\Domain\Model\Map\Point;
use PHPUnit\Framework\TestCase;

class MarkerTest extends TestCase
{
    public function testCreateMarker(): void
    {
        $marker = new Marker(
            new Point(48.86, 2.35),
            "Paris, capital of France",
            Color::BLUE
        );

        $this->assertEquals(new Point(48.86, 2.35), (string)$marker->getPoint());
        $this->assertEquals([48.86, 2.35], $marker->getCoords());
        $this->assertEquals("Paris, capital of France", $marker->getDescription());
        $this->assertEquals(Color::BLUE, $marker->getColor());
    }
}
