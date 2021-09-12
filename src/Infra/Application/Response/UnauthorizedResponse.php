<?php

namespace Rodrifarias\Blog\Infra\Application\Response;

use Fig\Http\Message\StatusCodeInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;

class UnauthorizedResponse extends Response
{
    public function __construct()
    {
        $handle = fopen("php://temp", "wb+");
        $body = new Stream($handle);
        $body->write(json_encode(['message' => 'Unauthorized']));
        $headers = new Headers(['Content-type' => 'application/problem+json']);
        parent::__construct(StatusCodeInterface::STATUS_UNAUTHORIZED, $headers, $body);
    }
}
