<?php

namespace Datamaps\Application\Presenter;

interface PresenterInterface
{
    public function write(ResponseInterface $response): void;
    public function read(): mixed;
}
