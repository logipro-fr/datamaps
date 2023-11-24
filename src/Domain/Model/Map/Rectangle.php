<?php

namespace Datamaps\Domain\Model\Map;

class Rectangle
{
    public function __construct(
        private readonly Point $first,
        private readonly Point $second
    ) {
    }

    /**
     * @return array<array<float>>
     */
    public function getBounds(): array
    {
        return [ $this->first->getCoords(), $this->second->getCoords() ];
    }
}
