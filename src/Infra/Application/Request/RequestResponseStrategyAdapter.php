<?php

namespace Rodrifarias\Blog\Infra\Application\Request;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rodrifarias\Blog\Infra\Application\Controller\BaseController;
use Slim\Interfaces\InvocationStrategyInterface;

class RequestResponseStrategyAdapter implements InvocationStrategyInterface
{
    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ): ResponseInterface {
        /** @var BaseController $baseController */
        $baseController = $callable[0];

        $baseController->setRequest($request);
        $baseController->setUser($request->getAttribute('user'));

        $paramsMethodController = array_values($routeArguments);

        return $callable(...$paramsMethodController);
    }
}
