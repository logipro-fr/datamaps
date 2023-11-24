<?php

namespace Datamaps\Infrastructure\Persistence\Doctrine\Types;

use Datamaps\Domain\Model\Map\MapId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class MapIdType extends Type
{
    public const TYPE_NAME = 'map_id';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param MapId $value
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value->getId();
    }

    /**
     * @param string $value
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): MapId
    {
        return new MapId($value);
    }

    /**
     * @param mixed[] $column
     * @return string
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }
}
