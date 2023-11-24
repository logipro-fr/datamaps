<?php

namespace Datamaps\Application\Service;

use Datamaps\Application\Presenter\ResponseInterface;

class Response implements ResponseInterface
{
    private const SUCCESS_CODE = 200;

    public function __construct(
        public readonly bool $success,
        public readonly object $data,
        public readonly int $error_code = self::SUCCESS_CODE,
        public readonly string $message = ""
    ) {
    }
}
