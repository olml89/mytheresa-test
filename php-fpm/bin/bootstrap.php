<?php

declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Dotenv\Dotenv;
use olml89\MyTheresaTest\Shared\Infrastructure\DatabaseConfig;
use olml89\MyTheresaTest\Shared\Infrastructure\Doctrine\EntityManagerProvider;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// @TODO: don't directly use phpdotenv here
$dotEnv = DotEnv::createImmutable(dirname(__DIR__));
$dotEnv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);

$containerBuilder->addDefinitions([

    DatabaseConfig::class => DI\factory(function (): DatabaseConfig {
        // @TODO: don't directly access $_ENV here
        return new DatabaseConfig(
            host: $_ENV['DB_HOST'],
            port: (int)$_ENV['DB_PORT'],
            database: $_ENV['DB_NAME'],
            username: $_ENV['DB_USER'],
            password: $_ENV['DB_PASSWORD'],
        );
    }),

    EntityManagerInterface::class => DI\factory(function (Container $container): EntityManagerInterface {
        /** @var EntityManagerProvider $entityManagerProvider */
        $entityManagerProvider = $container->get(EntityManagerProvider::class);

        return $entityManagerProvider->provide();
    })

]);

return $containerBuilder->build();
