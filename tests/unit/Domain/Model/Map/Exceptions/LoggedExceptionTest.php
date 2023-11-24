<?php

namespace Datamaps\Tests\Domain\Model\Map\Exceptions;

use Datamaps\Domain\Model\Map\Exceptions\LoggedException;
use Datamaps\Domain\Model\Map\Exceptions\MapNotFoundException;
use Datamaps\Infrastructure\Shared\CurrentWorkDirPath;
use PHPUnit\Framework\TestCase;

use function Safe\file_get_contents;

class LoggedExceptionTest extends TestCase
{
    public function testExceptionIsLogged(): void
    {
        $filepath = CurrentWorkDirPath::getPath() . LoggedException::LOG_FILE_PATH;
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        try {
            $this->throwLoggedException("Log test", 0);
        } catch (LoggedException $e) {
            $logs = file_get_contents($filepath);
            $this->assertStringEndsWith("0: Log test\n", $logs);
        }
    }

    private function throwLoggedException(string $message, int $code): void
    {
        throw new MapNotFoundException($message, $code);
    }
}
