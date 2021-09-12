<?php

namespace Tests\Infra\Application\Route\RoutesControllers\Controller;

use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Get;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Post;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\Middleware;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\PublicAccess;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\Route;

#[Route('/home')]
class HomeController
{
    #[Get, PublicAccess(true)]
    public function showAll(): void {}

    #[Get('/{id:\d+}'), PublicAccess(true)]
    public function show(int $id): void {}

    #[Post, Middleware([1])]
    public function create(): void {}
}
