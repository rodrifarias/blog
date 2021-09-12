<?php

namespace Rodrifarias\Blog\Domain\UseCase\Auth;

use Rodrifarias\Blog\Domain\UseCase\Auth\Exception\InvalidUsernamePasswordException;
use Rodrifarias\Blog\Domain\UseCase\Auth\InputOutputData\AuthUserCredentialsInputData;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInterface;

class AuthenticateUser
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @throws InvalidUsernamePasswordException
     */
    public function execute(AuthUserCredentialsInputData $authUserCredentialsInputData): void
    {
        $user = $this->userRepository->findByEmail($authUserCredentialsInputData->getEmail());
        $isValidUser = $user && password_verify($authUserCredentialsInputData->getPassword(), $user->getPassword());

        if (!$isValidUser) {
            throw new InvalidUsernamePasswordException();
        }
    }
}
