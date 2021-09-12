<?php

namespace Rodrifarias\Blog\Domain\UseCase\User;

use Ramsey\Uuid\Uuid;
use Rodrifarias\Blog\Domain\UseCase\User\Entity\User;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserExistsException;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UserOutputData;

class CreateUser extends AbstractUseCaseUser
{
    public function execute(CreateUserInputData $createUserInputData): UserOutputData
    {
        $existsUserWithEmail = $this->userRepository->findByEmail($createUserInputData->getEmail());

        if ($existsUserWithEmail) {
            throw new UserExistsException();
        }

        $idUser = Uuid::uuid4();
        $user = new User($idUser, $createUserInputData);
        $this->userRepository->save($user);

        return new UserOutputData($idUser, $user->name(), $user->email());
    }
}
