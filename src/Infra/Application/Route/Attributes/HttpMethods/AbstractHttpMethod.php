<?php

namespace Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods;

abstract class AbstractHttpMethod
{
    public function __construct(private string $path = '')
    {
    }
}
