<?php

namespace Datamaps\Domain\Model\Map\Exceptions;

use Datamaps\Infrastructure\Shared\CurrentWorkDirPath;
use Exception;
use Safe\DateTime;

use function Safe\error_log;

class LoggedException extends Exception
{
    public const LOG_FILE_PATH = "/log/exceptions.log";
    private const LOG_PATTERN = "[%s] %d: %s\n";

    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
        error_log($this->getMessageInFormat($message, $code), 3, CurrentWorkDirPath::getPath() . self::LOG_FILE_PATH);
    }

    private function getMessageInFormat(string $message, int $code): string
    {
        return sprintf(self::LOG_PATTERN, (new DateTime())->format("d.m.Y H:i:s"), $code, $message);
    }
}
