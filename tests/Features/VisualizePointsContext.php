<?php

namespace Features;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Datamaps\Application\Presenter\PresenterInterface;
use Datamaps\Application\Presenter\PresenterJson;
use Datamaps\Application\Service\DisplayMap\MapRequest;
use Datamaps\Application\Service\DisplayMap\MapService;
use Datamaps\Domain\Model\Map\Color;
use Datamaps\Domain\Model\Map\Layer;
use Datamaps\Domain\Model\Map\Map;
use Datamaps\Domain\Model\Map\MapId;
use Datamaps\Domain\Model\Map\MapRepositoryInterface;
use Datamaps\Domain\Model\Map\Marker;
use Datamaps\Domain\Model\Map\Point;
use Datamaps\Domain\Model\Map\Rectangle;
use Datamaps\Infrastructure\Api\V1\Map\Controller;
use Datamaps\Infrastructure\Persistence\Map\MapRepositoryInMemory;
use Datamaps\Tests\Domain\Model\Map\Builders\MapBuilder;
use Datamaps\Tests\Domain\Model\Map\Builders\MarkerBuilder;
use PHPUnit\Framework\Assert;

class VisualizePointsContext implements Context
{
    /** @var array<Map> $mapsWithoutLayerBuffer */
    private array $mapsWithoutLayerBuffer = [];

    /** @var array<Layer> $layersWithoutMapBuffer */
    private array $layersWithoutMapBuffer = [];

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
        /** @var array{"bottomleftlatitude":float,"bottomleftlongitude":float,"toprightlatitude":float,"toprightlongitude":float,"id":string} $row */
        foreach ($table as $row) {
            $this->mapsWithoutLayerBuffer[$row["id"]] =
                MapBuilder::aMap()
                    ->withBoundsAs(
                        new Rectangle(
                            new Point(
                                $row["bottomleftlatitude"],
                                $row["bottomleftlongitude"]
                            ),
                            new Point(
                                $row["toprightlatitude"],
                                $row["toprightlongitude"]
                            )
                        )
                    )->withId(new MapId($row["id"]))
                    ->build();
        }

        $this->tryLinkBuffers();
    }

    /**
     * @Given markers to display are located at :
     */
    public function markersToDisplayAreLocatedAt(TableNode $table): void
    {
        $markers = $this->putMarkersInArray($table);
        $this->addMarkersToMapLayer($markers);

        $this->tryLinkBuffers();
    }

    /** @return array<array<Marker>> */
    private function putMarkersInArray(TableNode $table): array
    {
        $markers = [];
        /** @var array{"latitude":float,"longitude":float,"description":string,"color":string,"mapid":string} $row */
        foreach ($table as $row) {
            $marker = $this->getMarker($row["latitude"], $row["longitude"], $row["description"], $row["color"]);
            $markers[$row["mapid"]][] = $marker;
        }
        return $markers;
    }

    private function getMarker(float $lat, float $lng, string $desc, string $color): Marker
    {
        return MarkerBuilder::aMarker()
                ->withPoint(new Point($lat, $lng))
                ->withDescription($desc)
                ->withColor(Color::from($color))
                ->build();
    }

    /** @param array<array<Marker>> $markers */
    private function addMarkersToMapLayer(array $markers): void
    {
        foreach ($markers as $name => $layerMarkers) {
            $this->layersWithoutMapBuffer[$name] = new Layer($name, $layerMarkers);
        }
    }

    private function tryLinkBuffers(): void
    {
        $buffersAreNotEmpty =
            sizeof($this->mapsWithoutLayerBuffer) > 0
            && sizeof($this->layersWithoutMapBuffer) > 0;
        if ($buffersAreNotEmpty) {
            $this->addEachLayerToCorrectMap();
        }
    }

    private function addEachLayerToCorrectMap(): void
    {
        foreach ($this->layersWithoutMapBuffer as $mapid => $layer) {
            $map = $this->mapsWithoutLayerBuffer[$mapid];
            $this->mapRepository->add(
                new Map(
                    $map->getBounds(),
                    $map->getMapId(),
                    [$layer]
                )
            );
        }
    }

    /**
     * @When the map of :mapid is asked for
     */
    public function theMapOfIsAskedFor(string $mapid): void
    {
        $this->mapController->execute(new MapRequest($mapid));
    }

    /**
     * @Then user can see a map of :mapid
     */
    public function userCanSeeAMapOf(string $mapid): void
    {
        $mapJson = $this->presenter->read();
        Assert::assertIsString($mapJson);
        $map = json_decode($mapJson);
        Assert::assertInstanceOf(\stdClass::class, $map);
        Assert::assertTrue($map->success);

        Assert::assertEquals($mapid, $map->data->mapId);
    }

    /**
     * @Then user can see markers on :mapid
     */
    public function userCanSeeMarkers(string $mapid): void
    {
        $mapJson = $this->presenter->read();
        Assert::assertIsString($mapJson);
        $map = json_decode($mapJson);
        Assert::assertInstanceOf(\stdClass::class, $map);
        Assert::assertEquals(
            count($this->mapRepository->findById(new MapId($mapid))->getLayers()[0]->getMarkers()),
            count($map->data->layers[0]->markers)
        );

        $this->checkEveryMarker($mapid, $map->data->layers[0]->markers);
    }

    /**
     * @param array<object> $markers
     */
    private function checkEveryMarker(string $mapid, array $markers): void
    {
        foreach ($this->mapRepository->findById(new MapId($mapid))->getLayers()[0]->getMarkers() as $marker) {
            $stdMarker = (object) array(
                "point" => $marker->getCoords(),
                "description" => $marker->getDescription(),
                "color" => $marker->getColor()->value
            );
            Assert::assertContainsEquals($stdMarker, $markers);
        }
    }
}
