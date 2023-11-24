<?php

namespace Datamaps\Tests\Infrastructure\Persistence\Doctrine;

use Datamaps\Infrastructure\Persistence\Doctrine\DoctrineMappingPath;
use PHPUnit\Framework\TestCase;

class DoctrineMappingPathTest extends TestCase
{
    private const DOCTRINE_MAPPING_PATH = '/src/Infrastructure/Persistence/Doctrine/Mapping';

    public function testGetFullPath(): void
    {
        $dmp = new DoctrineMappingPath();
        $path = $dmp->getFullPath();
        $this->assertStringEndsWith(self::DOCTRINE_MAPPING_PATH, $path);
    }
}
