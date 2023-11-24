<?php

namespace Datamaps\Domain\Model\Map;

class Marker
{
    public function __construct(
        private readonly Point $point,
        private readonly string $description,
        private readonly Color $color
    ) {
    }

    public function getPoint(): Point
    {
        return $this->point;
    }

    /** @return array<float> */
    public function getCoords(): array
    {
        return $this->getPoint()->getCoords();
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getColor(): Color
    {
        return $this->color;
    }
}
