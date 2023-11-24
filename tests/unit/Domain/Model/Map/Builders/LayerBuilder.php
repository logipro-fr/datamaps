<?php

namespace Datamaps\Tests\Domain\Model\Map\Builders;

use Datamaps\Domain\Model\Map\Layer;
use Datamaps\Domain\Model\Map\Marker;

class LayerBuilder
{
    private string $name;
    /** @var array<Marker> $markers */
    private array $markers;

    private function __construct()
    {
        $this->name = "default layer name";
        $this->markers = [];
    }

    public static function aLayer(): LayerBuilder
    {
        return new self();
    }

    public function build(): Layer
    {
        return new Layer(
            $this->name,
            $this->markers
        );
    }

    public function withName(string $name): LayerBuilder
    {
        $this->name = $name;
        return $this;
    }

    /** @param array<Marker> $markers */
    public function withMarkers(array $markers): LayerBuilder
    {
        $this->markers = $markers;
        return $this;
    }
}
