<?php

namespace Datamaps\Domain\Model\Map;

class Point
{
    private const PATTERN_STRING = "[%g,%g]";

    public function __construct(
        private readonly float $latitude,
        private readonly float $longitude
    ) {
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @return array<float>
     */
    public function getCoords(): array
    {
        return [$this->latitude, $this->longitude];
    }

    public function __toString(): string
    {
        return sprintf(self::PATTERN_STRING, $this->latitude, $this->longitude);
    }
}
