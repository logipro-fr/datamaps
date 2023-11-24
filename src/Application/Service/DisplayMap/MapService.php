<?php

namespace Datamaps\Application\Service\DisplayMap;

use Datamaps\Application\Service\AbstractService;
use Datamaps\Application\Service\RequestInterface;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapFactory;
use Datamaps\Domain\Model\Map\MapId;

class MapService extends AbstractService
{
    /** @param MapRequest $req */
    public function execute(RequestInterface $req): void
    {
        $map = $this->getRepository()->findById(new MapId($req->id));
        parent::writeResponse($this->getResponseData($map));
    }

    private function getResponseData(Map $map): \stdClass
    {
        $mapObject = MapFactory::createObjectFromMap($map);
        $mapObject->createdAt = $map->getCreationDate();
        return $mapObject;
    }
}
