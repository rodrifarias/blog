<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\Facade;

use Rodrifarias\Blog\Domain\UseCase\User\CreateUser;
use Rodrifarias\Blog\Domain\UseCase\User\DeleteUser;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UpdateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UserOutputData;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UserOutputDataCollection;
use Rodrifarias\Blog\Domain\UseCase\User\ShowAllUser;
use Rodrifarias\Blog\Domain\UseCase\User\ShowUser;
use Rodrifarias\Blog\Domain\UseCase\User\UpdateUser;

class UserActionFacade
{
    public function __construct(
        private CreateUser $createUser,
        private ShowAllUser $showAllUser,
        private ShowUser $showUser,
        private UpdateUser $updateUser,
        private DeleteUser $deleteUser,
    ) {
    }

    public function create(CreateUserInputData $createUserInputData): UserOutputData
    {
        return $this->createUser->execute($createUserInputData);
    }

    public function showAll(): UserOutputDataCollection
    {
        return $this->showAllUser->execute();
    }

    public function show(string $id): UserOutputData
    {
        return $this->showUser->execute($id);
    }

    public function update(string $id, UpdateUserInputData $updateUserInputData): void
    {
        $this->updateUser->execute($id, $updateUserInputData);
    }

    public function delete(string $id): void
    {
        $this->deleteUser->execute($id);
    }
}
