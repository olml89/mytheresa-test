<?php

declare(strict_types=1);

use Slim\App;

/** @var App $app */
$app = require dirname(__DIR__) . '/bootstrap/app.php';

$app->run();
