<?php

namespace Features;

use Behat\Behat\Context\Context;
use Datamaps\Application\Presenter\PresenterInterface;
use Datamaps\Application\Presenter\PresenterJson;
use Datamaps\Application\Service\CreateMap\CreateMapRequest;
use Datamaps\Application\Service\CreateMap\CreateMapService;
use Datamaps\Application\Service\DisplayMap\MapRequest;
use Datamaps\Application\Service\DisplayMap\MapService;
use Datamaps\Domain\Model\Map\MapFactory;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use PHPUnit\Framework\Assert;

use function Safe\file_get_contents;
use function Safe\json_decode;

class CreateMapContext implements Context
{
    private MapRepositoryInterface $mapRepository;
    private Controller $createMapController;
    private PresenterInterface $presenter;

    /**
     * @Given there is a map repository
     */
    public function thereIsAMapRepository(): void
    {
        $this->mapRepository = new MapRepositoryInMemory();
        $this->presenter = new PresenterJson();

        $this->createMapController = new Controller(new CreateMapService($this->mapRepository, $this->presenter));
    }

    /**
     * @When user asks for a new map from :filename
     */
    public function userAsksForANewMapFrom(string $filename): void
    {
        $mapRawData = file_get_contents("./tests/resources/" . $filename);
        $this->createMapController->execute(new CreateMapRequest($mapRawData));
    }

    /**
     * @Then the map is created according to :filename
     */
    public function theMapIsCreatedAccordingTo(string $filename): void
    {
        $mapData = json_decode(file_get_contents("./tests/resources/" . $filename));
        Assert::assertInstanceOf(\stdClass::class, $mapData);


        $presenter = new PresenterJson();
        $mapController = new Controller(new MapService($this->mapRepository, $presenter));
        $mapController->execute(new MapRequest($mapData->mapId));

        $mapResponse = json_decode($presenter->read());
        Assert::assertInstanceOf(\stdClass::class, $mapResponse);
        Assert::assertTrue(
            MapFactory::createMapFromObject($mapData)
                ->equals(MapFactory::createMapFromObject($mapResponse->data))
        );
    }

    /**
     * @Then user gets a response with id :expectedid
     */
    public function userGetsAResponseWithId(string $expectedid): void
    {
        $createdJson = $this->presenter->read();
        Assert::assertIsString($createdJson);
        $createMapResponse = json_decode($createdJson);
        Assert::assertInstanceOf(\stdClass::class, $createMapResponse);
        Assert::assertEquals($expectedid, $createMapResponse->data->mapId);
    }
}
