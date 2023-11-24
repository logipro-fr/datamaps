<?php

declare(strict_types=1);

namespace Datamaps\Tests\Infrastructure\Shared\Symfony;

use Datamaps\Infrastructure\Shared\Symfony\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

trait RunCommand
{
    public function runCommand(string $command): void
    {
        if (!is_file((string)getcwd() . '/vendor/autoload_runtime.php')) {
            throw new \LogicException('Symfony Runtime is missing. Try running "composer require symfony/runtime".');
        }

        require_once (string)getcwd() . '/vendor/autoload_runtime.php';
        $kernel = new Kernel("test", false);

        $app = new Application($kernel);
        $app->setAutoExit(false);
        $command = $command . ' --quiet';
        $app->run(new StringInput($command));
    }
}
