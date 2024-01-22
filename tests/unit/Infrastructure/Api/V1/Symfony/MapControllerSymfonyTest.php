<?php

namespace Datamaps\Tests\Infrastructure\Api\V1\Symfony;

use Datamaps\Application\Service\DisplayMap\MapService;
use Datamaps\Application\Service\Response;
use Datamaps\Infrastructure\Api\V1\Symfony\MapControllerSymfony;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function Safe\json_encode;

class MapControllerSymfonyTest extends TestCase
{
    public function testExecute(): void
    {
        /** @var MockObject */
        $mapService = $this->createMock(MapService::class);
        $mapService->expects($this->once())->method("execute");
        $mapService
            ->method("readResponse")
            ->willReturn($expectedResponse = json_encode(new Response(true, new \stdClass(), 200, "")));

        /** @var MapService $mapService */
        $controller = new MapControllerSymfony($mapService);
        $response = $controller->displayMap("map_id");
        $this->assertEquals($expectedResponse, $response->getContent());
    }
}
