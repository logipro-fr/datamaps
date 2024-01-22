<?php

namespace Datamaps\Tests\Infrastructure\Api\V1\Symfony;

use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Infrastructure\Api\V1\Symfony\MapControllerSymfony;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;
use PHPUnit\Framework\TestCase;

class MapControllerSymfonyTest extends TestCase
{
    public function testExecute(): void
    {
        $repository = new MapRepositoryInMemory();
        $repository->add(MapBuilder::aMap()->withId(new MapId("map_id"))->build());

        $controller = new MapControllerSymfony($repository);
        $response = $controller->displayMap("map_id");
        $this->assertNotFalse($response->getContent());

        /** @var \stdClass $responseObject */
        $responseObject = json_decode($response->getContent());

        $this->assertTrue($responseObject->success);
        $this->assertEquals("map_id", $responseObject->data->mapId);
    }

    public function testExecuteError(): void
    {
        $repository = new MapRepositoryInMemory();

        $controller = new MapControllerSymfony($repository);
        $response = $controller->displayMap("map_id");
        $this->assertNotFalse($response->getContent());

        /** @var \stdClass $responseObject */
        $responseObject = json_decode($response->getContent());

        $this->assertFalse($responseObject->success);
    }
}
