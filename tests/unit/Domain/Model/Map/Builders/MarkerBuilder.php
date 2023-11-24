<?php

namespace Datamaps\Tests\Domain\Model\Map\Builders;

use Datamaps\Domain\Model\Map\Color;
use Datamaps\Domain\Model\Map\Marker;
use Datamaps\Domain\Model\Map\Point;

class MarkerBuilder
{
    private Point $point;
    private string $description;
    private Color $color;

    private function __construct()
    {
        $this->point = new Point(0, 0);
        $this->description = "default marker description";
        $this->color = Color::BLUE;
    }

    public static function aMarker(): MarkerBuilder
    {
        return new self();
    }

    public function build(): Marker
    {
        return new Marker(
            $this->point,
            $this->description,
            $this->color
        );
    }

    public function withPoint(Point $point): MarkerBuilder
    {
        $this->point = $point;
        return $this;
    }

    public function withDescription(string $description): MarkerBuilder
    {
        $this->description = $description;
        return $this;
    }

    public function withColor(Color $color): MarkerBuilder
    {
        $this->color = $color;
        return $this;
    }
}
