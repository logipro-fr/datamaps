<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Map;

use Datamaps\Domain\Model\Map\Exceptions\EmptyRepositoryException;
use Datamaps\Domain\Model\Map\Exceptions\MapAlreadyExistsException;
use Datamaps\Domain\Model\Map\Exceptions\MapNotFoundException;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Domain\Model\Map\Point;
use Datamaps\Domain\Model\Map\Rectangle;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;
use PHPUnit\Framework\TestCase;
use Safe\DateTimeImmutable;

abstract class MapRepositoryTestBase extends TestCase
{
    protected MapRepositoryInterface $mapRepository;

    protected function setUp(): void
    {
        $this->initialize();
    }

    abstract protected function initialize(): void;

    public function testAddAndFind(): void
    {
        $mapId1 = new MapId("id1");
        $map = MapBuilder::aMap()->withId($mapId1)->build();
        $this->mapRepository->add($map);
        $this->assertTrue($mapId1->equals($this->mapRepository->findById($mapId1)->getMapId()));

        $mapId2 = new MapId("id2");
        $map = MapBuilder::aMap()->withId($mapId2)->build();
        $this->mapRepository->add($map);
        $this->assertTrue($mapId2->equals($this->mapRepository->findById($mapId2)->getMapId()));
    }

    public function testAddAndSort(): void
    {
        $this->initialize();

        $second = $this->addMapToRepository("06/06/2011 12:30:30");
        $first = $this->addMapToRepository("12/12/2022 23:59:59");
        $third = $this->addMapToRepository("01/01/2000 00:00:01");

        $maps = $this->mapRepository->getMapsInOrder();
        $this->assertCount(1, $maps);
        $this->assertTrue($first->equals($maps[0]));
    }

    public function testAddAndSortWithLimit(): void
    {
        $second = $this->addMapToRepository("06/06/2011 12:30:30");
        $first = $this->addMapToRepository("12/12/2022 23:59:59");
        $third = $this->addMapToRepository("01/01/2000 00:00:01");

        $maps = $this->mapRepository->getMapsInOrder(2);
        $this->assertCount(2, $maps);
        $this->assertTrue($first->equals($maps[0]));
        $this->assertTrue($second->equals($maps[1]));
    }

    public function testAddAndSortWithBiggerRequestThanInRepository(): void
    {
        $first = $this->addMapToRepository("12/12/2022 23:59:59");

        $maps = $this->mapRepository->getMapsInOrder(2);
        $this->assertCount(1, $maps);
        $this->assertTrue($first->equals($maps[0]));
    }

    private function addMapToRepository(string $date): Map
    {
        $map = new Map(
            new Rectangle(new Point(0, 0), new Point(0, 0)),
            createdAt: DateTimeImmutable::createFromFormat('d/m/Y H:i:s', $date)
        );
        $this->mapRepository->add($map);
        return $map;
    }

    public function testReinitializeRepository(): void
    {
        $map = MapBuilder::aMap()->withId(new MapId("id1"))->build();
        $this->mapRepository->add($map);

        $this->initialize();

        $this->expectException(MapNotFoundException::class);
        $this->expectExceptionMessage("Map with mapId 'id1' not found.");
        $this->expectExceptionCode(MapNotFoundException::ERROR_CODE);
        $this->mapRepository->findById(new MapId("id1"));
    }

    public function testMapNotFoundException(): void
    {
        $this->expectException(MapNotFoundException::class);
        $this->expectExceptionMessage("Map with mapId 'id1' not found.");
        $this->expectExceptionCode(MapNotFoundException::ERROR_CODE);
        $this->mapRepository->findById(new MapId("id1"));
    }

    public function testSearchInEmptyRepository(): void
    {
        $this->initialize();
        $this->expectException(EmptyRepositoryException::class);
        $this->expectExceptionMessage("Can't retrieve data from an empty repository");
        $this->expectExceptionCode(EmptyRepositoryException::ERROR_CODE);

        $this->mapRepository->getMapsInOrder();
    }

    public function testMapAlreadyExists(): void
    {
        $this->initialize();
        $map = MapBuilder::aMap()->withId(new MapId("original_new_map"))->build();
        $this->mapRepository->add($map);

        $this->expectException(MapAlreadyExistsException::class);
        $this->expectExceptionMessage("Can't create map: map already exists");
        $this->expectExceptionCode(MapAlreadyExistsException::ERROR_CODE);
        $this->mapRepository->add($map);
    }
}
