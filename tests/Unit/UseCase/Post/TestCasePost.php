<?php

namespace Tests\Unit\UseCase\Post;

use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Domain\UseCase\User\CreateUser;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UserOutputData;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInterface;

class TestCasePost extends TestCase
{
    private CreateUser $createUser;
    protected UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepositoryInMemory();
        $this->createUser = new CreateUser($this->userRepository);
    }

    protected function createUser(string $email = 'rodrigo.farias@teste.com'): UserOutputData
    {
        return $this->createUser->execute(CreateUserInputData::create([
            'name' => 'Rodrigo Farias',
            'email' => $email,
            'password' => '123'
        ]));
    }
}
