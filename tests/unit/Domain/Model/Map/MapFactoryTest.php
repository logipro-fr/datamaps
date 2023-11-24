<?php

namespace Datamaps\Tests\Domain\Model\Map;

use Datamaps\Domain\Model\Map\Color;
use Datamaps\Domain\Model\Map\MapFactory;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\Point;
use Datamaps\Domain\Model\Map\Rectangle;
use Datamaps\Tests\Domain\Model\Map\Builders\LayerBuilder;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;
use Datamaps\Tests\Domain\Model\Map\Builders\MarkerBuilder;
use PHPUnit\Framework\TestCase;

class MapFactoryTest extends TestCase
{
    public function testCreateMapFromObject(): void
    {
        $object = (object) array (
            "mapId" => "my_custom_map",
            "bounds" => [[1, 2], [3, 4]],
            "layers" => [
                (object) array(
                    "name" => "my_custom_layer",
                    "markers" => [
                        (object) array(
                            "point" => [-1, 1],
                            "description" => "my_custom_marker",
                            "color" => "red"
                        ),
                        (object) array(
                            "point" => [2, 3],
                            "description" => "my_second_custom_marker",
                            "color" => "blue"
                        )
                    ]
                ),
                (object) array(
                    "name" => "my_second_custom_layer",
                    "markers" => [
                        (object) array(
                            "point" => [0, 0],
                            "description" => "my_custom_marker",
                            "color" => "green"
                        )
                    ]
                )
            ]
        );

        $map = MapFactory::createMapFromObject($object);
        $this->assertEquals("my_custom_map", $map->getMapId());
        $this->assertEquals([[1, 2], [3, 4]], $map->getBoundsCoords());
        $layers = $map->getLayers();
        $this->assertEquals("my_custom_layer", $layers[0]->getName());
        $markers = $layers[0]->getMarkers();
        $this->assertEquals([-1, 1], $markers[0]->getCoords());
        $this->assertEquals("my_custom_marker", $markers[0]->getDescription());
        $this->assertEquals("red", $markers[0]->getColor()->value);
        $this->assertEquals([2, 3], $markers[1]->getCoords());
        $this->assertEquals("my_second_custom_marker", $markers[1]->getDescription());
        $this->assertEquals("blue", $markers[1]->getColor()->value);

        $this->assertEquals("my_second_custom_layer", $layers[1]->getName());
        $markers = $layers[1]->getMarkers();
        $this->assertEquals([0, 0], $markers[0]->getCoords());
        $this->assertEquals("my_custom_marker", $markers[0]->getDescription());
        $this->assertEquals("green", $markers[0]->getColor()->value);
    }

    public function testCreateObjectFromMap(): void
    {
        $map = MapBuilder::aMap()
            ->withId(new MapId("my_custom_map"))
            ->withBoundsAs(new Rectangle(new Point(1, 2), new Point(3, 4)))
            ->withLayer(
                LayerBuilder::aLayer()
                    ->withName("my_custom_layer")
                    ->withMarkers([
                        MarkerBuilder::aMarker()
                            ->withPoint(new Point(-1, 1))
                            ->withDescription("my_custom_marker")
                            ->withColor(Color::RED)
                            ->build(),
                        MarkerBuilder::aMarker()
                            ->withPoint(new Point(2, 3))
                            ->withDescription("my_second_custom_marker")
                            ->withColor(Color::BLUE)
                            ->build(),

                    ])
                    ->build()
            )
            ->withLayer(
                LayerBuilder::aLayer()
                    ->withName("my_second_custom_layer")
                    ->withMarkers([
                        MarkerBuilder::aMarker()
                            ->withPoint(new Point(0, 0))
                            ->withDescription("my_custom_marker")
                            ->withColor(Color::GREEN)
                            ->build()

                    ])
                    ->build()
            )
            ->build();

        $object = MapFactory::createObjectFromMap($map);
        $this->assertEquals("my_custom_map", $object->mapId);
        $this->assertEquals([[1, 2], [3, 4]], $object->bounds);
        $layers = $object->layers;
        $this->assertEquals("my_custom_layer", $layers[0]->name);
        $markers = $layers[0]->markers;
        $this->assertEquals([-1, 1], $markers[0]->point);
        $this->assertEquals("my_custom_marker", $markers[0]->description);
        $this->assertEquals("red", $markers[0]->color);
        $this->assertEquals([2, 3], $markers[1]->point);
        $this->assertEquals("my_second_custom_marker", $markers[1]->description);
        $this->assertEquals("blue", $markers[1]->color);

        $this->assertEquals("my_second_custom_layer", $layers[1]->name);
        $markers = $layers[1]->markers;
        $this->assertEquals([0, 0], $markers[0]->point);
        $this->assertEquals("my_custom_marker", $markers[0]->description);
        $this->assertEquals("green", $markers[0]->color);
    }
}
