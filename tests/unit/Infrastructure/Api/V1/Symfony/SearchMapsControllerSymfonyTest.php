<?php

namespace Datamaps\Tests\Infrastructure\Api\V1\Symfony;

use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Infrastructure\Api\V1\Symfony\SearchMapsControllerSymfony;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;
use PHPUnit\Framework\TestCase;

use function Safe\json_decode;

class SearchMapsControllerSymfonyTest extends TestCase
{
    public function testExecute(): void
    {
        $repository = new MapRepositoryInMemory();
        $repository->add(MapBuilder::aMap()->withId(new MapId("map_id_1"))->build());
        $repository->add(MapBuilder::aMap()->withId(new MapId("map_id_2"))->build());

        $controller = new SearchMapsControllerSymfony($repository);
        $response = $controller->searchMaps(2);
        $this->assertNotFalse($response->getContent());

        /** @var \stdClass $responseObject */
        $responseObject = json_decode($response->getContent());

        $this->assertTrue($responseObject->success);
        $this->assertCount(2, $responseObject->data->maps);
    }

    public function testExecuteError(): void
    {
        $repository = new MapRepositoryInMemory();

        $controller = new SearchMapsControllerSymfony($repository);
        $response = $controller->searchMaps(1);
        $this->assertNotFalse($response->getContent());

        /** @var \stdClass $responseObject */
        $responseObject = json_decode($response->getContent());

        $this->assertFalse($responseObject->success);
    }
}
