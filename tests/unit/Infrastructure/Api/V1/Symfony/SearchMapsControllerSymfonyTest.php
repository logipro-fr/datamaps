<?php

namespace Datamaps\Tests\Infrastructure\Api\V1\Symfony;

use Datamaps\Application\Presenter\PresenterJson;
use Datamaps\Application\Service\SearchMaps\SearchMapsService;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Api\V1\Symfony\SearchMapsControllerSymfony;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class SearchMapsControllerSymfonyTest extends TestCase
{
    public function testController(): void
    {
        /** @var MockObject */
        $controllerBase = $this->createMock(Controller::class);
        $controllerBase->expects($this->once())->method("execute");

        /** @var Controller $controllerBase */
        $controller = new SearchMapsControllerSymfonyFake($controllerBase, new MapRepositoryInMemory());
        $response = $controller->searchMaps(3);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testGetController(): void
    {
        $repository = new MapRepositoryInMemory();
        $controller = new SearchMapsControllerSymfony($repository);

        $presenter = new PresenterJson();
        $this->assertEquals(
            new Controller(new SearchMapsService($repository, $presenter)),
            $controller->getController()
        );
    }
}
