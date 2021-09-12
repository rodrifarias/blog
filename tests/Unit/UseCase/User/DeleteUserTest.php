<?php

namespace Tests\Unit\UseCase\User;

use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\User\CreateUser;
use Rodrifarias\Blog\Domain\UseCase\User\DeleteUser;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UserOutputData;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\User\ShowAllUser;
use Rodrifarias\Blog\Domain\UseCase\User\ShowUser;

class DeleteUserTest extends TestCase
{
    private CreateUser $createUser;
    private DeleteUser $deleteUser;
    private ShowUser $showUser;
    private ShowAllUser $showAllUser;

    protected function setUp(): void
    {
        $repository = new UserRepositoryInMemory();
        $postRepository = new PostRepositoryInMemory();
        $this->createUser = new CreateUser($repository);
        $this->deleteUser = new DeleteUser($repository, $postRepository);
        $this->showUser = new ShowUser($repository);
        $this->showAllUser = new ShowAllUser($repository);
    }

    public function testShouldGenerateUserNotFoundExceptionWhenDeleteUserNotExists(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->deleteUser->execute('123');
    }

    public function testShouldDeleteUser(): void
    {
        $userCreated = $this->createUser->execute(CreateUserInputData::create([
            'name' => 'Rodrigo Farias',
            'email' => 'rodrigo.farias@teste.com',
            'password' => 'qwe'
        ]));

        $user = $this->showUser->execute($userCreated->getId());
        $this->assertInstanceOf(UserOutputData::class, $user);
        $this->deleteUser->execute($user->getId());
        $this->assertCount(0, $this->showAllUser->execute()->users());
    }
}
