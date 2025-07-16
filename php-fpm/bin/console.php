<?php

declare(strict_types=1);

use DI\Container;
use olml89\MyTheresaTest\Product\Infrastructure\Console\LoadFixturesCommand;
use Symfony\Component\Console\Application;

/** @var Container $container */
$container = require 'bootstrap.php';

$application = new Application('Application CLI');

// @TODO: maybe don't load commands here
$application->add($container->get(LoadFixturesCommand::class));

return $application->run();
