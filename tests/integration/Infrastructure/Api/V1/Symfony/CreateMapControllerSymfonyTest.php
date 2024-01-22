<?php

namespace Datamaps\Tests\Integration\Infrastructure\Api\V1\Symfony;

use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryDoctrine;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function Safe\json_encode;

class CreateMapControllerSymfonyTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private MapRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["maps"]);

        $this->client = self::createClient(["debug" => false]);

        /** @var MapRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("map.repository");
        $this->repository = $autoInjectedRepo;
    }

    public function testCreateMapRoute(): void
    {
        $response = $this->client->request(
            "POST",
            "/api/v1/create",
            content: json_encode([
                "mapId" => "create_map_integration",
                "bounds" => [[1,2],[3,4]],
                "layers" => []
            ])
        );
        $this->assertStringContainsString('"success":true', $response->html());
        $this->assertStringContainsString('"mapId":"create_map_integration"', $response->html());

        $map = $this->repository->findById(new MapId("create_map_integration"));
        $this->assertEquals([[1,2],[3,4]], $map->getBoundsCoords());
    }
}
