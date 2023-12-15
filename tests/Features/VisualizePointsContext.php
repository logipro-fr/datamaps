<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Datamaps\Application\Presenter\PresenterInterface;
use Datamaps\Application\Presenter\PresenterJson;
use Datamaps\Application\Service\DisplayMap\MapRequest;
use Datamaps\Application\Service\DisplayMap\MapService;
use Datamaps\Domain\Model\Map\Color;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Domain\Model\Map\Marker;
use Datamaps\Domain\Model\Map\Point;
use Datamaps\Domain\Model\Map\Rectangle;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use Datamaps\Tests\Domain\Model\Map\Builders\LayerBuilder;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;
use Datamaps\Tests\Domain\Model\Map\Builders\MarkerBuilder;
use PHPUnit\Framework\Assert;

class VisualizePointsContext implements Context
{
    private MapRepositoryInterface $mapRepository;
    private PresenterInterface $presenter;
    private Controller $mapController;

    public function __construct()
    {
        $this->mapRepository = new MapRepositoryInMemory();

        $this->presenter = new PresenterJson();
        $this->mapController = new Controller(new MapService($this->mapRepository, $this->presenter));
    }

    /**
     * @Given some maps are defined by:
     */
    public function someMapsAreDefinedBy(TableNode $table): void
    {
        /** @var array{"bllatitude":float,"bllongitude":float,"trlatitude":float,"trlongitude":float,"id":string,"markers":string} $row */
        foreach ($table as $row) {
            $markers = $this->getMarkersFromString($row["markers"]);
            $this->mapRepository->add(
                MapBuilder::aMap()
                    ->withBoundsAs($this->getRectangleFromRow($row))
                    ->withId(new MapId($row["id"]))
                    ->withLayer(LayerBuilder::aLayer()
                        ->withMarkers($markers)
                        ->build())
                    ->build()
            );
        }
    }

    /**
     * @param array{"bllatitude":float,"bllongitude":float,"trlatitude":float,"trlongitude":float} $row
     */
    private function getRectangleFromRow(array $row): Rectangle
    {
        return new Rectangle(
            new Point(
                $row["bllatitude"],
                $row["bllongitude"]
            ),
            new Point(
                $row["trlatitude"],
                $row["trlongitude"]
            )
        );
    }

    /** @return array<Marker> */
    private function getMarkersFromString(string $markersString): array
    {
        $markers = [];
        $eachMarker = explode(" && ", $markersString);
        foreach ($eachMarker as $marker) {
            $eachParam = explode(" : ", $marker);
            $markers[] = MarkerBuilder::aMarker()
                ->withPoint(new Point(floatval($eachParam[0]), floatval($eachParam[1])))
                ->withDescription($eachParam[2])
                ->withColor(Color::from($eachParam[3]))
                ->build();
        }

        return $markers;
    }

    /**
     * @When the map of :mapid is asked for
     */
    public function theMapOfIsAskedFor(string $mapid): void
    {
        $this->mapController->execute(new MapRequest($mapid));
    }

    public function getResponseData(): \stdClass
    {
        $responseJson = $this->presenter->read();
        Assert::assertIsString($responseJson);
        $response = json_decode($responseJson);
        Assert::assertInstanceOf(\stdClass::class, $response);
        return $response->data;
    }

    /**
     * @Then user can see a map of :mapid
     */
    public function userCanSeeAMapOf(string $mapid): void
    {
        $map = $this->getResponseData();

        Assert::assertEquals($mapid, $map->mapId);
    }

    /**
     * @Then user should see :markers
     */
    public function userShouldSee(string $markers): void
    {
        $map = $this->getResponseData();
        $expectedMarkers = $this->getMarkersFromString($markers);

        Assert::assertTrue($this->markersArraysAreEqual($expectedMarkers, $map->layers[0]->markers));
    }

    /**
     * @param array<object{"point":array<float>,"description":string,"color":string}> $markersObj
     * @param array<Marker> $markers
     */
    private function markersArraysAreEqual(array $markers, array $markersObj): bool
    {
        if (sizeof($markersObj) != sizeof($markers)) {
            return false;
        } else {
            for ($i = 0; $i < sizeof($markersObj); $i++) {
                if ($this->markersAreEqual($markers[$i], $markersObj[$i]) == false) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param object{"point":array<float>,"description":string,"color":string} $actualMarker
     */
    private function markersAreEqual(Marker $expectedMarker, object $actualMarker): bool
    {
        $marker = (object) array(
            "point" => $expectedMarker->getCoords(),
            "description" => $expectedMarker->getDescription(),
            "color" => $expectedMarker->getColor()->value
        );
        if ($marker != $actualMarker) {
            return false;
        }
        return true;
    }
}
