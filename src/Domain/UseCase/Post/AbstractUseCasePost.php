<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post;

use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryInterface;

abstract class AbstractUseCasePost
{
    public function __construct(protected PostRepositoryInterface $postRepository)
    {
    }
}
