<?php

namespace Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Patch extends AbstractHttpMethod
{
}
