<?php

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Rodrifarias\Blog\Application\Service\Jwt\AbstractJWTService;
use Rodrifarias\Blog\Application\Service\LogApp\CreateLogAppInterface;
use Rodrifarias\Blog\Infra\Application\Handler\HttpErrorHandler;
use Rodrifarias\Blog\Infra\Application\Handler\ShutdownHandler;
use Rodrifarias\Blog\Infra\Application\Middleware\LogAppMiddleware;
use Rodrifarias\Blog\Infra\Application\Request\RequestPathRuleAdapter;
use Rodrifarias\Blog\Infra\Application\Response\UnauthorizedResponse;
use Rodrifarias\Blog\Infra\Application\Route\RouteInfo;
use Rodrifarias\Blog\Infra\Application\Route\RouteParsed;
use Rodrifarias\Blog\Infra\Application\Route\ScanRoutes;
use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;
use Tuupola\Middleware\JwtAuthentication;

return function (App $app) {

    $callableResolver = $app->getCallableResolver();
    $responseFactory = $app->getResponseFactory();

    $serverRequestCreator = ServerRequestCreatorFactory::create();
    $request = $serverRequestCreator->createServerRequestFromGlobals();

    $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
    $shutdownHandler = new ShutdownHandler($request, $errorHandler, getenv('APP_DISPLAY_ERRORS'));
    register_shutdown_function($shutdownHandler);

    $app->addRoutingMiddleware();

    $errorMiddleware = $app->addErrorMiddleware(getenv('APP_DISPLAY_ERRORS'), true, true);
    $errorMiddleware->setDefaultErrorHandler($errorHandler);

    $app->addBodyParsingMiddleware();
    $app->addErrorMiddleware(getenv('APP_DISPLAY_ERRORS'), true, true);

    $scan = new ScanRoutes();
    $routes = $scan->getRoutes(__DIR__ . '/../src/Application/Controller');
    $publicRoutes = array_filter($routes, fn (RouteInfo $r) => $r->isPublicAccess());
    $protectedRoutes = array_filter($routes, fn (RouteInfo $r) => !$r->isPublicAccess());

    $app->add(new JwtAuthentication([
        'secret' => getenv('APP_SECRET_KEY'),
        'secure' => false,
        'algorithm' => [ 'HS256' ],
        'rules' => [
            new RequestPathRuleAdapter([
                'ignore' => RouteParsed::getRoutesFromListRoutes($publicRoutes),
                'path' => RouteParsed::getRoutesFromListRoutes($protectedRoutes)
            ])
        ],
        'regexp' => '/Bearer\s+([A-Za-z0-9-_=\.]*)/i',
        'error' => fn (ResponseInterface $response) => new UnauthorizedResponse(),
        'before' => fn (RequestInterface $req, $args) => $req->withAttribute('user', json_encode($args['decoded']))
    ]));

    $app->add(new LogAppMiddleware(
        $app->getContainer()->get(CreateLogAppInterface::class),
        $app->getContainer()->get(AbstractJWTService::class),
    ));
};
