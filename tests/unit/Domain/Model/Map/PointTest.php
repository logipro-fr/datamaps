<?php

namespace Datamaps\Tests\Domain\Model\Map;

use Datamaps\Domain\Model\Map\Point;
use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    public function testCreatePoint(): void
    {
        $point = new Point(1, 2);
        $this->assertEquals(1, $point->getLatitude());
        $this->assertEquals(2, $point->getLongitude());
        $point = new Point(1.5, 2.5);
        $this->assertEquals(1.5, $point->getLatitude());
        $this->assertEquals(2.5, $point->getLongitude());
    }

    public function testToString(): void
    {
        $point = new Point(1.2, 2.3);
        $this->assertEquals("[1.2,2.3]", (string)$point);
        $point = new Point(3, 4);
        $this->assertEquals("[3,4]", (string)$point);
    }

    public function testCoordsArray(): void
    {
        $point = new Point(1, 2);
        $this->assertEquals([1, 2], $point->getCoords());
        $point = new Point(1.2, 2.3);
        $this->assertEquals([1.2, 2.3], $point->getCoords());
    }
}
