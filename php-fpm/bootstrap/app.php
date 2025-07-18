<?php

declare(strict_types=1);

use DI\Container;
use olml89\MyTheresaTest\Product\Infrastructure\Http\ListProductsController;
use olml89\MyTheresaTest\Product\Infrastructure\Http\ListProductsRequest;
use Psr\Http\Message\ResponseInterface;
use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/** @var Container $container */
$container = require dirname(__DIR__) . '/bootstrap/bootstrap.php';

// Setup container
AppFactory::setContainer($container);
$app = AppFactory::create();

// Configurate routes
$app->get(
    pattern: '/products',
    callable: function (Request $request, Response $response) use ($container): ResponseInterface {
        return ($container->get(ListProductsController::class))(new ListProductsRequest($request), $response);
    },
);

return $app;
