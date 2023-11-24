<?php

namespace Datamaps\Tests\Domain\Model\Map;

use Datamaps\Domain\Model\Map\Exceptions\WrongFormatException;
use Datamaps\Domain\Model\Map\MapFormat;
use Datamaps\Domain\Model\Map\MapId;
use PHPUnit\Framework\TestCase;

class MapFormatTest extends TestCase
{
    public function testCreateWithOnlyBounds(): void
    {
        $json = '{
            "bounds": [[1, 2], [3, 4]]
        }';

        $mapFormat = MapFormat::fromJSON($json);

        $this->assertStringStartsWith(MapId::PREFIX_NAME, $mapFormat->mapId);
        $this->assertEquals([[1, 2], [3, 4]], $mapFormat->bounds);
    }

    public function testCreateWithIdAndBounds(): void
    {
        $json = '{
            "mapId": "my_map_to_format",
            "bounds": [[1.3, 2.8], [3.4521, 4.625]]
        }';

        $mapFormat = MapFormat::fromJSON($json);

        $this->assertEquals("my_map_to_format", $mapFormat->mapId);
        $this->assertEquals([[1.3, 2.8], [3.4521, 4.625]], $mapFormat->bounds);
    }

    public function testCreateWithBoundsAndLayers(): void
    {
        $json = '{
            "bounds": [[1, 2], [3, 4]],
            "layers": [
                {
                    "name": "my_custom_first_layer",
                    "markers": [
                        {
                          "point": [45.73, 4.84],
                          "description": "Lyon",
                          "color": "blue"
                        }
                    ]
                }
            ]
        }';

        $mapFormat = MapFormat::fromJSON($json);

        $this->assertEquals([[1, 2], [3, 4]], $mapFormat->bounds);
        $this->assertEquals(
            [(object) array(
                "name" => "my_custom_first_layer",
                "markers" => [
                    (object) array(
                      "point" => [45.73, 4.84],
                      "description" => "Lyon",
                      "color" => "blue"
                    )
                ]
            )],
            $mapFormat->layers
        );
    }

    public function testCreateFromEmptyJson(): void
    {
        $this->expectException(WrongFormatException::class);
        $this->expectExceptionMessage("/: The required properties (bounds) are missing");

        $json = '{

        }';

        $mapFormat = MapFormat::fromJSON($json);
    }

    public function testWrongFormatWithWrongBounds(): void
    {
        $this->expectException(WrongFormatException::class);
        $this->expectExceptionMessage("/bounds/1: The data (integer) must match the type: array");

        $json = '{
            "bounds": [[2, 4], 1]
        }';

        $mapFormat = MapFormat::fromJSON($json);
    }

    public function testWrongFormatWithShortID(): void
    {
        $this->expectException(WrongFormatException::class);
        $this->expectExceptionMessage("/mapId: Minimum string length is 5, found 3");

        $json = '{
            "mapId": "map",
            "bounds": [[1, 2], [3, 4]]
        }';

        $mapFormat = MapFormat::fromJSON($json);
    }

    public function testWrongFormatWithLayersWithoutMarkers(): void
    {
        $this->expectException(WrongFormatException::class);
        $this->expectExceptionMessage("/layers/0: The required properties (markers) are missing");

        $json = '{
            "bounds": [[1, 2], [3, 4]],
            "layers": [
                {
                    "name": "my_custom_first_layer"
                }
            ]
        }';

        $mapFormat = MapFormat::fromJSON($json);
    }
}
