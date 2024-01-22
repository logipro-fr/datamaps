<?php

namespace Datamaps\Infrastructure\Api\V1\Symfony;

use Datamaps\Application\Presenter\PresenterJson;
use Datamaps\Application\Service\DisplayMap\MapRequest;
use Datamaps\Application\Service\DisplayMap\MapService;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapControllerSymfony
{
    public function __construct(
        private MapService $mapService
    ) {
    }

    #[Route('/api/v1/display/{mapId}', "display_map", methods: ['GET'])]
    public function displayMap(string $mapId): Response
    {
        $this->mapService->execute(new MapRequest($mapId));

        /** @var string */
        $response = $this->mapService->readResponse();
        return new Response($response);
    }
}
