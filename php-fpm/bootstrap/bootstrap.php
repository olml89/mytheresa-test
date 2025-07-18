<?php

declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use olml89\MyTheresaTest\Product\Domain\ProductRepository;
use olml89\MyTheresaTest\Product\Infrastructure\Doctrine\ProductDoctrineRepository;
use olml89\MyTheresaTest\Shared\Domain\ApplicationContext;
use olml89\MyTheresaTest\Shared\Domain\Environment;
use olml89\MyTheresaTest\Shared\Domain\EnvironmentLoader;
use olml89\MyTheresaTest\Shared\Infrastructure\Environment\PhpdotenvEnvironmentLoader;
use olml89\MyTheresaTest\Shared\Infrastructure\Persistence\DatabaseConfig;
use olml89\MyTheresaTest\Shared\Infrastructure\Persistence\Doctrine\EntityManagerProvider;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);

$containerBuilder->addDefinitions([

    EnvironmentLoader::class => DI\autowire(PhpdotenvEnvironmentLoader::class),

    ApplicationContext::class => DI\factory(function (Container $c): ApplicationContext {
        /** @var EnvironmentLoader $environmentLoader */
        $environmentLoader = $c->get(EnvironmentLoader::class);

        return new ApplicationContext(
            environment: Environment::tryFrom($environmentLoader->string('APP_ENV')) ?? Environment::Production,
            rootDir: dirname(__DIR__),
        );
    }),

    DatabaseConfig::class => DI\factory(function (Container $c): DatabaseConfig {
        /** @var EnvironmentLoader $environmentLoader */
        $environmentLoader = $c->get(EnvironmentLoader::class);

        return new DatabaseConfig(
            host: $environmentLoader->string('DB_HOST'),
            port: $environmentLoader->int('DB_PORT'),
            database: $environmentLoader->string('DB_NAME'),
            username: $environmentLoader->string('DB_USER'),
            password: $environmentLoader->string('DB_PASSWORD'),
        );
    }),

    EntityManagerInterface::class => DI\factory(function (Container $container): EntityManagerInterface {
        /** @var EntityManagerProvider $entityManagerProvider */
        $entityManagerProvider = $container->get(EntityManagerProvider::class);

        return $entityManagerProvider->provide();
    }),

    ProductRepository::class => DI\autowire(ProductDoctrineRepository::class),

]);

$container = $containerBuilder->build();

/** @var EnvironmentLoader $environmentLoader */
$environmentLoader = $container->get(EnvironmentLoader::class);
$environmentLoader->load(dirname(__DIR__));

return $container;
