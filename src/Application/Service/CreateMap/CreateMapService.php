<?php

namespace Datamaps\Application\Service\CreateMap;

use Datamaps\Application\Service\AbstractService;
use Datamaps\Application\Service\RequestInterface;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapFactory;

use function Safe\json_decode;

class CreateMapService extends AbstractService
{
    private const INITIAL_PATH = "/api/v1/display/";

    /** @param CreateMapRequest $req */
    public function execute(RequestInterface $req): void
    {
        $map = $this->createMapInRepository($req);
        parent::writeResponse($this->getResponseData($map));
    }

    private function createMapInRepository(CreateMapRequest $createMapRequest): Map
    {
        /** @var \stdClass $mapObject */
        $mapObject = json_decode($createMapRequest->json);
        $map = MapFactory::createMapFromObject($mapObject);
        $this->getRepository()->add($map);
        return $map;
    }

    private function getResponseData(Map $map): \stdClass
    {
        return (object) array(
            "mapId" => $map->getMapId()->getId(),
            "displayUrl" => $this->getDisplayUrl($map->getMapId())
        );
    }

    private function getDisplayUrl(string $mapId): string
    {
        return CreateMapService::INITIAL_PATH . $mapId;
    }
}
