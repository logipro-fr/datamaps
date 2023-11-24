<?php

namespace Datamaps\Domain\Model\Map;

class MapId
{
    private string $id;

    public const PREFIX_NAME = "dm_map_";
    public const MAX_SIZE = 36;
    public const BYTE_PER_CHAR = 2;

    public function __construct(?string $id = null)
    {
        if ($id == null) {
            $this->id = $this->uniqid();
        } else {
            $this->id = $id;
        }
    }

    private function uniqid(): string
    {
        $id = bin2hex(random_bytes(
            (int)((self::MAX_SIZE - strlen(self::PREFIX_NAME)) / self::BYTE_PER_CHAR)
        ));
        return self::PREFIX_NAME . $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function equals(MapId $other): bool
    {
        return $this->id == $other->id;
    }

    public function __toString(): string
    {
        return $this->getId();
    }
}
