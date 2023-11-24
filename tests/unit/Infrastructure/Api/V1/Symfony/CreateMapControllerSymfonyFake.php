<?php

namespace Datamaps\Tests\Infrastructure\Api\V1\Symfony;

use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Api\V1\Symfony\CreateMapControllerSymfony;

class CreateMapControllerSymfonyFake extends CreateMapControllerSymfony
{
    public function __construct(
        private Controller $controller,
        private MapRepositoryInterface $mapRepository,
    ) {
        parent::__construct($this->mapRepository);
    }

    public function getController(): Controller
    {
        return $this->controller;
    }
}
