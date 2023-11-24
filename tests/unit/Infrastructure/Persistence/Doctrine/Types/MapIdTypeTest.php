<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Doctrine\Types;

use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Infrastructure\Persistence\Doctrine\Types\MapIdType;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;

class MapIdTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('map_id', (new MapIdType())->getName());
    }

    public function testConvertToPHPValue(): void
    {
        $type = new MapIdType();
        $id = $type->convertToPHPValue("map_", new SqlitePlatform());
        $this->assertEquals(true, $id instanceof MapId);
    }

    public function testConvertToDatabaseValue(): void
    {
        $type = new MapIdType();
        $dbValue = $type->convertToDatabaseValue($id = new MapId(), new SqlitePlatform());
        $this->assertEquals($id->__toString(), $dbValue);
    }
}
