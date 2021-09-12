<?php

use Rodrifarias\Blog\Infra\Application\Route\ScanRoutes;
use Slim\App;

return function (App $app) {
    $scan = new ScanRoutes();
    $routes = $scan->getRoutes(__DIR__ . '/../src/Application/Controller');

    foreach ($routes as $route) {
        $method = $route->getHttpMethod();
        $appRoute = $app->$method($route->getPath(), [ $route->getClassName(), $route->getClassMethod() ]);

        foreach ($route->getMiddleware() as $middleware) {
            $appRoute->add(new $middleware());
        }
    }
};
