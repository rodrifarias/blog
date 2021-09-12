<?php

namespace Rodrifarias\Blog\Infra\Application\Route\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class PublicAccess
{
    public function __construct(private bool $value = false)
    {
    }
}
