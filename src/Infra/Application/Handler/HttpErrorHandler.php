<?php

namespace Rodrifarias\Blog\Infra\Application\Handler;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Handlers\ErrorHandler;

class HttpErrorHandler extends ErrorHandler
{
    public const BAD_REQUEST = 'BAD_REQUEST';
    public const NOT_ALLOWED = 'NOT_ALLOWED';
    public const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    public const SERVER_ERROR = 'SERVER_ERROR';
    public const UNAUTHENTICATED = 'UNAUTHENTICATED';

    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;
        $statusCode = $exception->getCode() ?: StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;
        $description = $exception->getMessage() ?: 'Internal error';

        $type = match ($statusCode) {
            StatusCodeInterface::STATUS_NOT_FOUND => self::RESOURCE_NOT_FOUND,
            StatusCodeInterface::STATUS_UNAUTHORIZED => self::UNAUTHENTICATED,
            StatusCodeInterface::STATUS_BAD_REQUEST => self::BAD_REQUEST,
            StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED => self::NOT_ALLOWED,
            default => self::SERVER_ERROR
        };

        $error = ['statusCode' => $statusCode, 'error' => $type, 'description' => $description];

        if ($this->displayErrorDetails) {
            $error['trace'] = $exception->getTraceAsString();
        }

        $payload = json_encode($error, JSON_PRETTY_PRINT);
        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
