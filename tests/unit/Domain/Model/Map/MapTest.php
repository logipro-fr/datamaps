<?php

namespace Datamaps\Tests\Domain\Model\Map;

use Datamaps\Domain\Model\Map\Event\MapCreated;
use Datamaps\Domain\Model\Map\Layer;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\Point;
use Datamaps\Domain\Model\Map\Rectangle;
use Datamaps\Tests\Domain\SpySubscriber;
use Phariscope\Event\EventPublisher;
use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;

class MapTest extends TestCase
{
    /** @param array<Layer> $layers */
    private function createMap(
        float $p1lat = 0,
        float $p1lng = 0,
        float $p2lat = 1,
        float $p2lng = 1,
        MapId $id = new MapId(),
        DateTimeImmutable $time = new DateTimeImmutable(),
        array $layers = []
    ): Map {
        return new Map(
            new Rectangle(new Point($p1lat, $p1lng), new Point($p2lat, $p2lng)),
            $id,
            $layers,
            $time
        );
    }

    public function testCreateMap(): void
    {
        $map = new Map(
            bounds: new Rectangle(
                new Point(1, 2),
                new Point(3, 4)
            )
        );
        $this->assertInstanceOf(Map::class, $map);
    }

    public function testMapHasMapId(): void
    {
        $map = $this->createMap(id: new MapId("1"));
        $this->assertTrue((new MapId("1"))->equals($map->getMapId()));
    }

    public function testMapHasBound(): void
    {
        $map = $this->createMap(1, 2, 3, 4);

        $this->assertInstanceOf(Rectangle::class, $map->getBounds());
        $this->assertEquals([[1,2], [3,4]], $map->getBoundsCoords());
    }

    public function testMapHasLayer(): void
    {
        $layers = [new Layer("Cities", [])];
        $map = $this->createMap(layers: $layers);

        $this->assertEquals($layers, $map->getLayers());
    }

    public function testMapIsCreatedAt(): void
    {
        $creationTime = DateTimeImmutable::createFromFormat('d/m/Y H:i:s', "12/03/2022 15:32:45");
        $map = $this->createMap(time: $creationTime);
        $this->assertEquals($creationTime, $map->getCreatedAt());
    }

    public function testMapHasCreationDate(): void
    {
        $creationTime = DateTimeImmutable::createFromFormat('d/m/Y H:i:s', "12/03/2022 15:32:45");
        $map = $this->createMap(time: $creationTime);
        $this->assertEquals($creationTime->format(DateTimeImmutable::ATOM), $map->getCreationDate());
    }

    public function testMapEquality(): void
    {
        $map1 = $this->createMap(id: new MapId("map1"));
        $this->assertTrue($map1->equals($this->createMap(id: new MapId("map1"))));

        $map2 = $this->createMap(id: new MapId("map2"));
        $this->assertFalse($map2->equals($this->createMap(id: new MapId("map2_wrong"))));

        $map3 = $this->createMap(0, 0, 0, 0);
        $this->assertFalse($map3->equals($this->createMap(1, 1, 1, 1)));

        $map4 = $this->createMap(
            id: new MapId("map4"),
            layers: [new Layer("layer", [])]
        );
        $this->assertFalse($map4->equals($this->createMap(id: new MapId("map4"))));
    }

    public function testMapCreatedEvent(): void
    {
        $spy = new SpySubscriber();
        EventPublisher::instance()->subscribe($spy);
        $map = $this->createMap();
        EventPublisher::instance()->distribute();
        $event = $spy->domainEvent;
        $this->assertInstanceOf(MapCreated::class, $event);
        /** @var MapCreated $event */
        $this->assertEquals((string)$map->getMapId(), $event->mapId);
    }
}
