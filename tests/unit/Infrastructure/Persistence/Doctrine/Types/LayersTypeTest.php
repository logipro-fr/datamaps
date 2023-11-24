<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Doctrine\Types;

use Datamaps\Domain\Model\Map\Layer;
use Datamaps\Infrastructure\Persistence\Doctrine\Types\LayersType;
use Datamaps\Tests\Domain\Model\Map\Builders\LayerBuilder;
use Datamaps\Tests\Domain\Model\Map\Builders\MarkerBuilder;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use PHPUnit\Framework\TestCase;

class LayersTypeTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('layers', (new LayersType())->getName());
    }

    public function testConvertValue(): void
    {
        $type = new LayersType();
        $dbValue = $type->convertToDatabaseValue(
            $layers = $this->getExampleLayers(),
            new SqlitePlatform()
        );
        $this->assertIsString($dbValue);
        $phpValue = $type->convertToPHPValue($dbValue, new SqlitePlatform());
        $this->assertEquals($layers, $phpValue);
    }

    /** @return array<Layer> */
    private function getExampleLayers(): array
    {
        $layers = [
            LayerBuilder::aLayer()
                ->withMarkers(
                    array(MarkerBuilder::aMarker()->build(), MarkerBuilder::aMarker()->build())
                )
                ->build(),
            LayerBuilder::aLayer()->withName("second_layer")->build()
        ];
        return $layers;
    }
}
