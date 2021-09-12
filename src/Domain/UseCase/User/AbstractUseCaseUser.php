<?php

namespace Rodrifarias\Blog\Domain\UseCase\User;

use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInterface;

abstract class AbstractUseCaseUser
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }
}
