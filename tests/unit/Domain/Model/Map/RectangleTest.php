<?php

namespace Datamaps\Tests\Domain\Model\Map;

use Datamaps\Domain\Model\Map\Point;
use Datamaps\Domain\Model\Map\Rectangle;
use PHPUnit\Framework\TestCase;

class RectangleTest extends TestCase
{
    public function testCreateRectangle(): void
    {
        $rectangle = new Rectangle(
            new Point(1, 2),
            new Point(3, 4)
        );
        $this->assertEquals([[1,2],[3,4]], $rectangle->getBounds());

        $rectangle = new Rectangle(
            new Point(2, 3),
            new Point(4, 5)
        );
        $this->assertEquals([[2,3],[4,5]], $rectangle->getBounds());
    }

    public function testInvertPoints(): void
    {
        $rectangle1 = new Rectangle(
            new Point(1, 2),
            new Point(3, 4)
        );
        $rectangle2 = new Rectangle(
            new Point(3, 4),
            new Point(1, 2)
        );

        $this->assertNotEquals($rectangle1->getBounds(), $rectangle2->getBounds());
    }
}
