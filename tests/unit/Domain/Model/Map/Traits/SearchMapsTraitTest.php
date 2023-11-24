<?php

namespace Datamaps\Tests\Domain\Model\Map\Traits;

use Datamaps\Domain\Model\Map\Exceptions\EmptyRepositoryException;
use PHPUnit\Framework\TestCase;

class SearchMapsTraitTest extends TestCase
{
    public function testSearchMapsTrait(): void
    {
        $trait = new SearchMapsTraitClass();
        $maps = $trait->getMapsInOrder();
        $this->assertCount(1, $maps);

        $maps = $trait->getMapsInOrder(3);
        $this->assertCount(3, $maps);
    }

    public function testCantSearchMapsTrait(): void
    {
        $this->expectException(EmptyRepositoryException::class);

        $trait = new SearchMapsTraitClass(true);
        $maps = $trait->getMapsInOrder();
    }
}
