<?php

namespace Datamaps\Infrastructure\Persistence\Doctrine\Types;

use Datamaps\Domain\Model\Map\Layer;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

use function Safe\gzdeflate;
use function Safe\gzinflate;

class LayersType extends Type
{
    public const TYPE_NAME = 'layers';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /**
     * @param array<Layer> $value
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return gzdeflate(serialize($value), 1);
    }

    /**
     * @param string $value
     * @return array<Layer>
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): array
    {
        /** @var array<Layer> */
        $layers = unserialize(gzinflate($value));
        return $layers;
    }

    /**
     * @param mixed[] $column
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "blob";
    }
}
