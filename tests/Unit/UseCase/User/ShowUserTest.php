<?php

namespace Tests\Unit\UseCase\User;

use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Domain\UseCase\User\CreateUser;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UserOutputData;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\User\ShowUser;

class ShowUserTest extends TestCase
{
    private CreateUser $createUser;
    private ShowUser $showUser;

    protected function setUp(): void
    {
        $repository = new UserRepositoryInMemory();
        $this->createUser = new CreateUser($repository);
        $this->showUser = new ShowUser($repository);
    }

    public function testShouldShowUser(): void
    {
        $userCreate = $this->createUser->execute(CreateUserInputData::create([
            'name' => 'ROdrigo Farias',
            'email' => 'rodrigo.farias@teste.com',
            'password' => 'qwe'
        ]));

        $user = $this->showUser->execute($userCreate->getId());
        $this->assertInstanceOf(UserOutputData::class, $user);
    }

    public function testShouldGenerateUserNotFoundExceptionWhenUserNotExists(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectErrorMessage('User not found');
        $this->showUser->execute(uniqid());
    }
}
