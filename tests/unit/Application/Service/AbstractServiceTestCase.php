<?php

namespace Datamaps\Tests\Application\Service;

use Datamaps\Application\Service\AbstractService;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use PHPUnit\Framework\TestCase;

abstract class AbstractServiceTestCase extends TestCase
{
    protected MapRepositoryInterface $repository;
    protected AbstractService $service;

    public function setUp(): void
    {
        $this->repository = new MapRepositoryInMemory();
        $this->fillRepository();
        $this->createService();
    }

    abstract protected function fillRepository(): void;

    abstract protected function createService(): void;

    protected function getDataFromSuccessfulResponse(): \stdClass
    {
        $response = $this->service->readResponse();
        $this->assertInstanceOf(\stdClass::class, $response);

        $this->assertTrue($response->success);
        return $response->data;
    }
}
