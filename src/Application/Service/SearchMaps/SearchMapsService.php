<?php

namespace Datamaps\Application\Service\SearchMaps;

use Datamaps\Application\Service\AbstractService;
use Datamaps\Application\Service\RequestInterface;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapFactory;

class SearchMapsService extends AbstractService
{
    /** @param SearchMapsRequest $req */
    public function execute(RequestInterface $req): void
    {
        $maps = $this->getRepository()->getMapsInOrder($req->count);
        parent::writeResponse($this->getResponseData($maps));
    }

    /** @param array<Map> $maps */
    private function getResponseData(array $maps): \stdClass
    {
        return (object) array(
            "maps" => $this->mapsToObjects($maps)
        );
    }

    /**
     * @param array<Map> $maps
     * @return array<\stdClass>
     */
    private function mapsToObjects(array $maps): array
    {
        $mapsObjects = [];
        foreach ($maps as $map) {
            $mapsObjects[] = MapFactory::createObjectFromMap($map);
        }
        return $mapsObjects;
    }
}
