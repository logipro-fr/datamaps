<?php

namespace Datamaps\Tests\Integration\Infrastructure\Api\V1\Symfony;

use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryDoctrine;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MapControllerSymfonyTest extends WebTestCase
{
    use DoctrineRepositoryTesterTrait;

    private KernelBrowser $client;
    private MapRepositoryInterface $repository;
    private EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        $this->initDoctrineTester();
        $this->clearTables(["maps"]);

        $this->client = self::createClient(["debug" => false]);

        /** @var MapRepositoryDoctrine $autoInjectedRepo */
        $autoInjectedRepo = $this->client->getContainer()->get("map.repository");
        $this->repository = $autoInjectedRepo;

        /** @var EntityManagerInterface $em */
        $em = $this->client->getContainer()->get("doctrine.orm.entity_manager");
        $this->entityManager = $em;
    }

    public function testDisplayMapRoute(): void
    {
        $this->repository->add(MapBuilder::aMap()->withId(new MapId("display_map_integration"))->build());
        $this->entityManager->flush();

        $response = $this->client->request("GET", "/api/v1/display/display_map_integration");
        $this->assertStringContainsString('"success":true', $response->html());
        $this->assertStringContainsString('"mapId":"display_map_integration"', $response->html());
    }
}
