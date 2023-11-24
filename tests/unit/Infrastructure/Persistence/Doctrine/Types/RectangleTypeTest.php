<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Doctrine\Types;

use Datamaps\Domain\Model\Map\Point;
use Datamaps\Domain\Model\Map\Rectangle;
use Datamaps\Infrastructure\Persistence\Doctrine\Types\RectangleType;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;

class RectangleTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('rectangle', (new RectangleType())->getName());
    }

    public function testConvertValue(): void
    {
        $type = new RectangleType();
        $dbValue = $type->convertToDatabaseValue(
            $rectangle = new Rectangle(new Point(42, -5), new Point(51, 10)),
            new SqlitePlatform()
        );
        $this->assertIsString($dbValue);
        $phpValue = $type->convertToPHPValue($dbValue, new SqlitePlatform());
        $this->assertEquals($rectangle, $phpValue);
    }
}
