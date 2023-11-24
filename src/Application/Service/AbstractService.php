<?php

namespace Datamaps\Application\Service;

use Datamaps\Application\Presenter\PresenterInterface;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;

abstract class AbstractService
{
    public function __construct(
        private MapRepositoryInterface $repository,
        private PresenterInterface $presenter
    ) {
    }

    abstract public function execute(RequestInterface $req): void;

    /** @param \stdClass $data */
    protected function writeResponse(\stdClass $data): void
    {
        $this->getPresenter()->write(new Response(
            true,
            $data
        ));
    }

    public function getPresenter(): PresenterInterface
    {
        return $this->presenter;
    }

    public function readResponse(): mixed
    {
        return $this->getPresenter()->read();
    }

    protected function getRepository(): MapRepositoryInterface
    {
        return $this->repository;
    }
}
