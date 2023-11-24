<?php

namespace Datamaps\Application\Presenter;

use function Safe\json_decode;
use function Safe\json_encode;

class PresenterObject implements PresenterInterface
{
    private ResponseInterface $response;

    public function write(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function read(): \stdClass
    {
        /** @var \stdClass */
        $response = json_decode(json_encode($this->response));
        return $response;
    }
}
