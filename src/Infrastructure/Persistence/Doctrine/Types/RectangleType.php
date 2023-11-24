<?php

namespace Datamaps\Infrastructure\Persistence\Doctrine\Types;

use Datamaps\Domain\Model\Map\Rectangle;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class RectangleType extends Type
{
    public const TYPE_NAME = 'rectangle';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param Rectangle $value
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return serialize($value);
    }

    /**
     * @param string $value
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): Rectangle
    {
        /** @var Rectangle */
        $rect = unserialize($value);
        return $rect;
    }

    /**
     * @param mixed[] $column
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "text";
    }
}
