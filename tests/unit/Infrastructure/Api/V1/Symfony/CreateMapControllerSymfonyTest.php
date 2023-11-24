<?php

namespace Datamaps\Tests\Infrastructure\Api\V1\Symfony;

use Datamaps\Application\Presenter\PresenterJson;
use Datamaps\Application\Service\CreateMap\CreateMapService;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Api\V1\Symfony\CreateMapControllerSymfony;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryDoctrine;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateMapControllerSymfonyTest extends TestCase
{
    public function testDoctrineRepositoryIsFlushed(): void
    {
        $doctrineRepository = $this->createMock(MapRepositoryDoctrine::class);
        $doctrineRepository->expects($this->once())->method("flush");

        /** @var MockObject */
        $controllerBase = $this->createMock(Controller::class);
        $controllerBase->expects($this->once())->method("execute");

        /** @var Controller $controllerBase */
        $controller = new CreateMapControllerSymfonyFake($controllerBase, $doctrineRepository);
        $response = $controller->createMap(Request::create("", content: "{}"));
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testController(): void
    {
        $mapRepository = new MapRepositoryInMemory();

        /** @var MockObject */
        $controllerBase = $this->createMock(Controller::class);
        $controllerBase->expects($this->once())->method("execute");

        /** @var Controller $controllerBase */
        $controller = new CreateMapControllerSymfonyFake($controllerBase, $mapRepository);
        $response = $controller->createMap(Request::create("", content: "{}"));
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testGetController(): void
    {
        $repository = new MapRepositoryInMemory();
        $controller = new CreateMapControllerSymfony($repository);

        $presenter = new PresenterJson();
        $this->assertEquals(
            new Controller(new CreateMapService($repository, $presenter)),
            $controller->getController()
        );
    }
}
