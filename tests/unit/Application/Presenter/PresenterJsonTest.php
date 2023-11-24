<?php

namespace Datamaps\Tests\Application\Presenter;

use Datamaps\Application\Presenter\PresenterJson;

class PresenterJsonTest extends PresenterTestBase
{
    protected function initialize(): void
    {
        $this->presenter = new PresenterJson();
    }

    protected function decode(mixed $encodedResponse): \stdClass
    {
        $this->assertIsString($encodedResponse);
        $decoded = json_decode($encodedResponse);
        $this->assertInstanceOf(\stdClass::class, $decoded);
        return $decoded;
    }
}
