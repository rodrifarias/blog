<?php

namespace Rodrifarias\Blog\Domain\UseCase\Auth;

use DateTime;
use Rodrifarias\Blog\Application\Service\Jwt\AbstractJWTService;
use Rodrifarias\Blog\Domain\UseCase\Auth\InputOutputData\UserCredentialsOutputData;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInterface;

class CreateCredentialsUser
{
    public function __construct(private UserRepositoryInterface $userRepository, private AbstractJWTService $userJwtService)
    {
    }

    public function execute(string $email, DateTime $expiresIn): UserCredentialsOutputData
    {
        $user = $this->userRepository->findByEmail($email);
        $payload = ['idUser' => $user->getId(), 'name' => $user->getName()];
        $token = $this->userJwtService->createToken($payload, $expiresIn);

        return new UserCredentialsOutputData($user->getEmail(), $user->getName(), $token);
    }
}
