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
        private MapRepositoryInterface $repository
    ) {
    }

    #[Route('/api/display/{mapId}', "display_map", methods: ['GET'])]
    public function displayMap(string $mapId): Response
    {
        $controller = $this->getController();
        $controller->execute(new MapRequest($mapId));

        /** @var string */
        $response = $controller->readResponse();
        return new Response($response);
    }

    public function getController(): Controller
    {
        $presenter = new PresenterJson();
        return new Controller(new MapService($this->repository, $presenter));
    }
}
