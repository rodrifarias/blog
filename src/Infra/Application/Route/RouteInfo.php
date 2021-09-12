<?php

namespace Rodrifarias\Blog\Infra\Application\Route;

class RouteInfo
{
    public function __construct(
        private string $className,
        private string $classMethod,
        private string $httpMethod,
        private string $path,
        private bool $publicAccess,
        private array $middleware,
    ) {
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getClassMethod(): string
    {
        return $this->classMethod;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function isPublicAccess(): bool
    {
        return $this->publicAccess;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }
}
