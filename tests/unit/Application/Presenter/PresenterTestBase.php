<?php

namespace Datamaps\Tests\Application\Presenter;

use Datamaps\Application\Presenter\PresenterInterface;
use Datamaps\Application\Presenter\ResponseInterface;
use PHPUnit\Framework\TestCase;

abstract class PresenterTestBase extends TestCase
{
    protected PresenterInterface $presenter;

    protected function setUp(): void
    {
        $this->initialize();
    }

    abstract protected function initialize(): void;

    public function testWriteAndRead(): void
    {
        $this->writeInPresenter(new ResponseFake("string", 2.56, []));
        $response = $this->readFromPresenter();
        $expected = (object) array(
            "stringValue" => "string",
            "floatValue" => 2.56,
            "arrayValue" => []
        );
        $this->assertEquals($expected, $response);

        $this->writeInPresenter(new ResponseFake("gnirts/\\", 6.4, ["array0", [4]]));
        $response = $this->readFromPresenter();
        $expected = (object) array(
            "stringValue" => "gnirts/\\",
            "floatValue" => 6.4,
            "arrayValue" => ["array0", [4]]
        );
        $this->assertEquals($expected, $response);
    }

    private function writeInPresenter(ResponseInterface $response): void
    {
        $this->presenter->write($response);
    }

    private function readFromPresenter(): \stdClass
    {
        return $this->decode($this->presenter->read());
    }

    abstract protected function decode(mixed $encodedResponse): \stdClass;
}
