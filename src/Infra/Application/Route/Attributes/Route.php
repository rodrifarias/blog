<?php

namespace Rodrifarias\Blog\Infra\Application\Route\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Route
{
    public function __construct(private string $prefixRoute)
    {
    }
}
