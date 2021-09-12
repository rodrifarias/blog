<?php

use Dotenv\Dotenv;
use Rodrifarias\Blog\Infra\Application\Request\RequestResponseStrategyAdapter;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$container = require_once __DIR__ . '/config/container.php';
$routes = require_once __DIR__ . '/config/routes.php';
$middleware = require_once __DIR__ . '/config/middleware.php';

$app = AppFactory::createFromContainer($container);
$app->getRouteCollector()->setDefaultInvocationStrategy(new RequestResponseStrategyAdapter());
$middleware($app);
$routes($app);

return $app;
