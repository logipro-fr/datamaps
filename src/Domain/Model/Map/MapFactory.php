<?php

namespace Datamaps\Domain\Model\Map;

class MapFactory
{
    public static function createObjectFromMap(Map $map): \stdClass
    {
        $object = (object) array(
            "mapId" => $map->getMapId()->getId(),
            "bounds" => $map->getBoundsCoords(),
            "layers" => self::createObjectsFromLayers($map->getLayers())
        );

        return $object;
    }

    /**
     * @param array<Layer> $layers
     * @return array<\stdClass>
     */
    private static function createObjectsFromLayers(array $layers): array
    {
        $objects = [];
        foreach ($layers as $layer) {
            $objects[] = (object) array(
                "name" => $layer->getName(),
                "markers" => self::createObjectsFromMarkers($layer->getMarkers())
            );
        }

        return $objects;
    }

    /**
     * @param array<Marker> $markers
     * @return array<\stdClass>
     */
    private static function createObjectsFromMarkers(array $markers): array
    {
        $objects = [];
        foreach ($markers as $marker) {
            $objects[] = (object) array(
                "point" => $marker->getCoords(),
                "description" => $marker->getDescription(),
                "color" => $marker->getColor()->value
            );
        }

        return $objects;
    }

    public static function createMapFromObject(\stdClass $object): Map
    {
        $mapObject = new MapFormat($object);
        return self::createMapFromFormat($mapObject);
    }

    private static function createMapFromFormat(MapFormat $mapObject): Map
    {
        $map = new Map(
            new Rectangle(
                new Point($mapObject->bounds[0][0], $mapObject->bounds[0][1]),
                new Point($mapObject->bounds[1][0], $mapObject->bounds[1][1])
            ),
            new MapId($mapObject->mapId),
            self::createLayersFromFormat($mapObject->layers)
        );

        return $map;
    }

    /**
     * @param array<\stdClass> $layersObjects
     * @return array<Layer>
    */
    private static function createLayersFromFormat(array $layersObjects): array
    {
        $layers = [];

        foreach ($layersObjects as $layerObject) {
            $layers[] = new Layer(
                $layerObject->name,
                self::createMarkersFromFormat($layerObject->markers)
            );
        }

        return $layers;
    }

    /**
     * @param array<\stdClass> $markersObjects
     * @return array<Marker>
    */
    private static function createMarkersFromFormat(array $markersObjects): array
    {
        $markers = [];

        foreach ($markersObjects as $markerObject) {
            $markers[] = new Marker(
                new Point($markerObject->point[0], $markerObject->point[1]),
                $markerObject->description,
                Color::from($markerObject->color)
            );
        }

        return $markers;
    }
}
