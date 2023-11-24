<?php

namespace Datamaps\Tests\Infrastructure\Api\V1\Symfony;

use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Api\V1\Symfony\MapControllerSymfony;

class MapControllerSymfonyFake extends MapControllerSymfony
{
    public function __construct(
        private Controller $controller,
        private MapRepositoryInterface $repository
    ) {
        parent::__construct($this->repository);
    }

    public function getController(): Controller
    {
        return $this->controller;
    }
}
