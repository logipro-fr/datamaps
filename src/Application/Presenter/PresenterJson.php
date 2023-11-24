<?php

namespace Datamaps\Application\Presenter;

use function Safe\json_encode;

class PresenterJson implements PresenterInterface
{
    private ResponseInterface $response;

    public function write(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function read(): string
    {
        return json_encode($this->response);
    }
}
