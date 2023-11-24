<?php

namespace Datamaps\Tests\Domain\Model\Map\Traits;

use Datamaps\Domain\Model\Map\Exceptions\MapAlreadyExistsException;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;
use PHPUnit\Framework\TestCase;

class AddMapTraitTest extends TestCase
{
    public function testAddMapTrait(): void
    {
        $trait = new AddMapTraitClass();
        $trait->add(MapBuilder::aMap()->build());
        $this->assertTrue($trait->mapCreated);
    }

    public function testCantAddMapTrait(): void
    {
        $this->expectException(MapAlreadyExistsException::class);

        $trait = new AddMapTraitClass(false);
        $trait->add(MapBuilder::aMap()->build());
    }
}
