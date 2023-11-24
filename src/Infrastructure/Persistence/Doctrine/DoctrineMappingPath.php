<?php

namespace Datamaps\Infrastructure\Persistence\Doctrine;

use Datamaps\Infrastructure\Shared\CurrentWorkDirPath;

class DoctrineMappingPath
{
    private const DOCTRINE_MAPPING_PATH = '/src/Infrastructure/Persistence/Doctrine/Mapping';

    public function getFullPath(): string
    {
        return CurrentWorkDirPath::getPath() . self::DOCTRINE_MAPPING_PATH;
    }
}
