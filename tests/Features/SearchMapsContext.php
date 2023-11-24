<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Datamaps\Application\Presenter\PresenterInterface;
use Datamaps\Application\Presenter\PresenterObject;
use Datamaps\Application\Service\SearchMaps\SearchMapsRequest;
use Datamaps\Application\Service\SearchMaps\SearchMapsService;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Domain\Model\Map\Point;
use Datamaps\Domain\Model\Map\Rectangle;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use PHPUnit\Framework\Assert;
use Safe\DateTimeImmutable;

class SearchMapsContext implements Context
{
    private MapRepositoryInterface $mapRepository;

    private Controller $searchMapsController;
    private PresenterInterface $presenter;
    private const MAPS_ORDER = ["first", "second", "third", "fourth", "fifth"];
    private const DATE_PATTERN = 'd/m/Y H:i:s';

    public function __construct()
    {
        $this->mapRepository = new MapRepositoryInMemory();

        $this->presenter = new PresenterObject();
        $this->searchMapsController = new Controller(new SearchMapsService($this->mapRepository, $this->presenter));
    }

    /**
     * @Given some maps are defined by id and creation date:
     */
    public function someMapsAreDefinedByIdAndCreationDate(TableNode $table): void
    {
        /** @var array{"id":string,"creation_time":string} $row */
        foreach ($table as $row) {
            $date = DateTimeImmutable::createFromFormat(self::DATE_PATTERN, $row["creation_time"]);
            $date = $date == false ? new DateTimeImmutable() : $date;

            $this->mapRepository->add(
                new Map(
                    new Rectangle(new Point(0, 0), new Point(1, 1)),
                    new MapId($row["id"]),
                    createdAt: $date
                )
            );
        }
    }

    /**
     * @When user asks for the youngest map
     */
    public function userAsksForTheYoungestMap(): void
    {
        $this->searchMapsController->execute(new SearchMapsRequest());
    }

    /**
     * @Then user receives it
     */
    public function userReceivesIt(): void
    {
        $mapsResponse = $this->presenter->read();
        Assert::assertInstanceOf(\stdClass::class, $mapsResponse);
        $maps = $mapsResponse->data->maps;
        foreach ($maps as $i => $map) {
            Assert::assertTrue(
                (new MapId(self::MAPS_ORDER[$i]))
                ->equals(new MapId($map->mapId))
            );
        }
    }

    /**
     * @When user asks only for the :limit youngest maps
     */
    public function userAsksOnlyForTheYoungestMaps(int $limit): void
    {
        $this->searchMapsController->execute(new SearchMapsRequest($limit));
    }

    /**
     * @Then user receives :amount maps in creation order
     */
    public function userReceivesMapsInCreationOrder(int $amount): void
    {
        $mapsResponse = $this->presenter->read();
        Assert::assertInstanceOf(\stdClass::class, $mapsResponse);
        $maps = $mapsResponse->data->maps;

        Assert::assertCount($amount, $maps);
        foreach ($maps as $i => $map) {
            Assert::assertTrue(
                (new MapId(self::MAPS_ORDER[$i]))
                ->equals(new MapId($map->mapId))
            );
        }
    }
}
