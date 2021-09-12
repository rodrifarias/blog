<?php

namespace Rodrifarias\Blog\Domain\UseCase\User;

use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UserOutputData;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UserOutputDataCollection;

class ShowAllUser extends AbstractUseCaseUser
{
    public function execute(): UserOutputDataCollection
    {
        $users = $this->userRepository->findAll();
        $userCollection = new UserOutputDataCollection();

        foreach ($users as $user) {
            $userCollection->add(new UserOutputData($user->getId(), $user->getName(), $user->getEmail()));
        }

        return $userCollection;
    }
}
