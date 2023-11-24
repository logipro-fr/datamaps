<?php

namespace Datamaps\Tests\Infrastructure\Api\V1\Symfony;

use Datamaps\Application\Presenter\PresenterJson;
use Datamaps\Application\Service\DisplayMap\MapService;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Api\V1\Symfony\MapControllerSymfony;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class MapControllerSymfonyTest extends TestCase
{
    public function testController(): void
    {
        /** @var MockObject */
        $controllerBase = $this->createMock(Controller::class);
        $controllerBase->expects($this->once())->method("execute");

        /** @var Controller $controllerBase */
        $controller = new MapControllerSymfonyFake($controllerBase, new MapRepositoryInMemory());
        $response = $controller->displayMap("map_id");
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testGetController(): void
    {
        $repository = new MapRepositoryInMemory();
        $controller = new MapControllerSymfony($repository);

        $presenter = new PresenterJson();
        $this->assertEquals(
            new Controller(new MapService($repository, $presenter)),
            $controller->getController()
        );
    }
}
