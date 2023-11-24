<?php

namespace Datamaps\Tests\Application\Presenter;

use Datamaps\Application\Presenter\PresenterObject;

class PresenterObjectTest extends PresenterTestBase
{
    protected function initialize(): void
    {
        $this->presenter = new PresenterObject();
    }

    protected function decode(mixed $encodedResponse): \stdClass
    {
        $decoded = $encodedResponse;
        $this->assertInstanceOf(\stdClass::class, $decoded);
        return $decoded;
    }
}
