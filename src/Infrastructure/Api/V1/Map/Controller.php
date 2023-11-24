<?php

namespace Datamaps\Infrastructure\Api\V1\Map;

use Datamaps\Application\Presenter\PresenterInterface;
use Datamaps\Application\Service\AbstractService;
use Datamaps\Application\Service\RequestInterface;
use Datamaps\Application\Service\Response;
use Exception;

class Controller
{
    public function __construct(
        private AbstractService $service
    ) {
    }

    public function execute(RequestInterface $request): void
    {
        try {
            $this->service->execute($request);
        } catch (Exception $e) {
            $this->writeUnsuccessfulResponse($e);
        }
    }

    private function writeUnsuccessfulResponse(Exception $e): void
    {
        $mapResponse = new Response(
            false,
            new \stdClass(),
            $e->getCode(),
            $e->getMessage()
        );
        $this->getPresenter()->write($mapResponse);
    }

    private function getPresenter(): PresenterInterface
    {
        return $this->service->getPresenter();
    }

    public function readResponse(): mixed
    {
        return $this->service->readResponse();
    }
}
