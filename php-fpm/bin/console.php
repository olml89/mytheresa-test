<?php

declare(strict_types=1);

use DI\Container;
use olml89\MyTheresaTest\Shared\Infrastructure\Console\CommandIterator;
use Symfony\Component\Console\Application;

/** @var Container $container */
$container = require dirname(__DIR__) . '/bootstrap/bootstrap.php';

/** @var CommandIterator $commandIterator */
$commandIterator = $container->get(CommandIterator::class);

$application = new Application('Application CLI');

foreach ($commandIterator as $commandInfo) {
    $application->add($commandInfo->command());
}

return $application->run();
