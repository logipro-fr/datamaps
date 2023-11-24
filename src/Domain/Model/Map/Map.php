<?php

namespace Datamaps\Domain\Model\Map;

use Datamaps\Domain\Model\Map\Event\MapCreated;
use Phariscope\Event\EventPublisher;
use DateTimeImmutable;

class Map
{
    private const DATE_PATTERN = DateTimeImmutable::ATOM;


    /** @param array<Layer> $layers */
    public function __construct(
        private readonly Rectangle $bounds,
        private readonly MapId $mapId = new MapId(),
        private readonly array $layers = [],
        private readonly DateTimeImmutable $createdAt = new DateTimeImmutable()
    ) {
        EventPublisher::instance()->publish(new MapCreated($mapId));
    }

    public function getMapId(): MapId
    {
        return $this->mapId;
    }

    public function getBounds(): Rectangle
    {
        return $this->bounds;
    }

    /**
     * @return array<array<float>>
     */
    public function getBoundsCoords(): array
    {
        return $this->bounds->getBounds();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCreationDate(): string
    {
        return $this->getCreatedAt()->format(self::DATE_PATTERN);
    }

    /**
     * @return array<Layer>
     */
    public function getLayers(): array
    {
        return $this->layers;
    }

    /**
     * Compare everything but the creation time
     */
    public function equals(Map $other): bool
    {
        if ($this->getMapId()->equals($other->getMapId())) {
            if ($this->getBounds() == $other->getBounds()) {
                if ($this->getLayers() == $other->getLayers()) {
                    return true;
                }
            }
        }
        return false;
    }
}
