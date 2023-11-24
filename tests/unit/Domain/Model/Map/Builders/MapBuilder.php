<?php

namespace Datamaps\Tests\Domain\Model\Map\Builders;

use Datamaps\Domain\Model\Map\Layer;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapFormat;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\Point;
use Datamaps\Domain\Model\Map\Rectangle;
use Safe\DateTimeImmutable;

class MapBuilder
{
    private Rectangle $rect;
    private MapId $mapId;
    private DateTimeImmutable $createdAt;

    /** @var array<Layer> $layers */
    private array $layers;

    private function __construct()
    {
        $this->rect = new Rectangle(new Point(0, 0), new Point(1, 1));
        $this->mapId = new MapId();
        $this->createdAt = new DateTimeImmutable();
        $this->layers = [];
    }

    public static function aMap(): MapBuilder
    {
        return new self();
    }

    public function build(): Map
    {
        $map = new Map(
            $this->rect,
            $this->mapId,
            $this->layers,
            $this->createdAt
        );

        return $map;
    }

    public function withBoundsAs(Rectangle $rect): MapBuilder
    {
        $this->rect = $rect;
        return $this;
    }

    public function withId(MapId $id): MapBuilder
    {
        $this->mapId = $id;
        return $this;
    }

    public function withLayer(Layer $layer): MapBuilder
    {
        $this->layers[] = $layer;
        return $this;
    }
}
