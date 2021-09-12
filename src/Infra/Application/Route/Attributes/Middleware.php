<?php

namespace Rodrifarias\Blog\Infra\Application\Route\Attributes;

use Attribute;
use Psr\Http\Server\MiddlewareInterface;
use ReflectionClass;
use ReflectionException;
use Rodrifarias\Blog\Infra\Exception\MiddlewareShouldImplementsMiddlewareInterfaceException;

#[Attribute(Attribute::TARGET_METHOD)]
class Middleware
{
    /**
     * @param string[] $middlewares wrapperClass
     * @throws ReflectionException
     * @throws MiddlewareShouldImplementsMiddlewareInterfaceException
     */
    public function __construct(private array $middlewares)
    {
        $middlewaresImplementsMiddlewareInterface = array_filter($middlewares, function ($m)  {
            if (!is_string($m)) {
                return false;
            }

            $reflectionClass = new ReflectionClass($m);
            return $reflectionClass->implementsInterface(MiddlewareInterface::class);
        });

        if (count($middlewaresImplementsMiddlewareInterface) !== count($this->middlewares)) {
            throw new MiddlewareShouldImplementsMiddlewareInterfaceException();
        }
    }
}
