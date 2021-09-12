<?php

namespace Rodrifarias\Blog\Domain\UseCase\User;

use Rodrifarias\Blog\Domain\UseCase\User\Exception\EmailExistsException;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UpdateUserInputData;

class UpdateUser extends AbstractUseCaseUser
{
    /**
     * @throws UserNotFoundException
     */
    public function execute(string $id, UpdateUserInputData $updateUserInputData): void
    {
        $user = $this->userRepository->findById($id);
        $emailUserExists = $this->userRepository->findByEmail($updateUserInputData->getEmail());

        if ($emailUserExists && ($emailUserExists->getId() !== $user->getId())) {
            throw new EmailExistsException();
        }

        $someEmail = $user->getEmail() === $updateUserInputData->getEmail();
        $email = !$someEmail ? $updateUserInputData->getEmail() : null;

        $this->userRepository->update($id, $updateUserInputData->getName(), $email);
    }
}
