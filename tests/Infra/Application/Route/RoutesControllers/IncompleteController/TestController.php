<?php

namespace Tests\Infra\Application\Route\RoutesControllers\IncompleteController;

use Rodrifarias\Blog\Infra\Application\Route\Attributes\Route;

#[Route('/test')]
class TestController
{
    public function showAll(): void {}
}
