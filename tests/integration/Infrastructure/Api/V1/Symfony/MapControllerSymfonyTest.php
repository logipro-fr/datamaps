<?php

namespace Datamaps\Tests\Infrastructure\Api\V1\Symfony;

use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryDoctrine;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MapControllerSymfonyTest extends WebTestCase
{
    private KernelBrowser $client;
    private MapRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->client = self::createClient(["debug" => false]);
        /** @var MapRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("map.repository");
        $this->repository = $autoInjectedRepo;
    }

    public function testDisplayMapRoute(): void
    {
        $this->repository->add(MapBuilder::aMap()->withId(new MapId("display_map_integration"))->build());

        $response = $this->client->request("GET", "/api/v1/display/display_map_integration");
        $this->assertStringContainsString('"success":true', $response->html());
    }
}
