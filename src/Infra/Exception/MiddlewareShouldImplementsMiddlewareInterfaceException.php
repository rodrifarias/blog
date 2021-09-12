<?php

namespace Rodrifarias\Blog\Infra\Exception;

use Fig\Http\Message\StatusCodeInterface;

class MiddlewareShouldImplementsMiddlewareInterfaceException extends InfraException
{
    public function __construct()
    {
        parent::__construct('Middleware should implements MiddlewareInterface', StatusCodeInterface::STATUS_BAD_REQUEST);
    }
}
