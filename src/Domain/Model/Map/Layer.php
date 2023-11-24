<?php

namespace Datamaps\Domain\Model\Map;

class Layer
{
    /**
     * @param array<Marker> $markers
     */
    public function __construct(
        private readonly string $name,
        private readonly array $markers
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<Marker>
     */
    public function getMarkers(): array
    {
        return $this->markers;
    }
}
