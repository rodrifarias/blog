<?php

namespace Rodrifarias\Blog\Domain\UseCase\User;

use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UserOutputData;

class ShowUser extends AbstractUseCaseUser
{
    /**
     * @throws UserNotFoundException
     */
    public function execute(string $id): UserOutputData
    {
        $user = $this->userRepository->findById($id);
        return new UserOutputData($user->getId(), $user->getName(), $user->getEmail());
    }
}
