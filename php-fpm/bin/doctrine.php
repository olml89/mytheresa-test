<?php

declare(strict_types=1);

use DI\Container;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Symfony\Component\Console\Application;

/** @var Container $container */
$container = require dirname(__DIR__) . '/bootstrap/bootstrap.php';

/** @var EntityManagerInterface $entityManager */
$entityManager = $container->get(EntityManagerInterface::class);
$entityManagerProvider = new SingleManagerProvider($entityManager);

$application = new Application('Doctrine ORM');
ConsoleRunner::addCommands($application, $entityManagerProvider);

return $application->run();
