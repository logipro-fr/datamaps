<?php

namespace Datamaps\Infrastructure\Api\V1\Symfony;

use Datamaps\Application\Presenter\PresenterJson;
use Datamaps\Application\Service\SearchMaps\SearchMapsRequest;
use Datamaps\Application\Service\SearchMaps\SearchMapsService;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchMapsControllerSymfony
{
    public function __construct(
        private MapRepositoryInterface $repository
    ) {
    }

    #[Route('/api/search/{amount}', "search_maps", methods: ['GET'])]
    public function searchMaps(int $amount): Response
    {
        $controller = $this->getController();
        $controller->execute(new SearchMapsRequest($amount));
        /** @var string */
        $response = $controller->readResponse();
        return new Response($response);
    }

    public function getController(): Controller
    {
        $presenter = new PresenterJson();
        return new Controller(new SearchMapsService($this->repository, $presenter));
    }
}
