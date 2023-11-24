<?php

namespace Datamaps\Infrastructure\Api\V1\Symfony;

use Datamaps\Application\Presenter\PresenterJson;
use Datamaps\Application\Service\CreateMap\CreateMapRequest;
use Datamaps\Application\Service\CreateMap\CreateMapService;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryDoctrine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateMapControllerSymfony
{
    public function __construct(
        private MapRepositoryInterface $repository
    ) {
    }

    #[Route('/api/create', "create_map", methods: ['POST'])]
    public function createMap(Request $req): Response
    {
        $controller = $this->getController();
        $controller->execute(new CreateMapRequest($req->getContent()));
        if ($this->repository instanceof MapRepositoryDoctrine) {
            $this->repository->flush();
        }

        /** @var string */
        $response = $controller->readResponse();
        return new Response($response);
    }

    public function getController(): Controller
    {
        $presenter = new PresenterJson();
        return new Controller(new CreateMapService($this->repository, $presenter));
    }
}
