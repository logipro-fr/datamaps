<?php

namespace Datamaps\Tests\Application\Presenter;

use Datamaps\Application\Presenter\ResponseInterface;

class ResponseFake implements ResponseInterface
{
    /**
     * @param array<mixed> $arrayValue
     */
    public function __construct(
        public readonly string $stringValue,
        public readonly float $floatValue,
        public readonly array $arrayValue
    ) {
    }
}
