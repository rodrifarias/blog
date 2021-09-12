<?php

namespace Tests\Unit\UseCase\User;

use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Domain\UseCase\User\CreateUser;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\EmailExistsException;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UpdateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\User\ShowUser;
use Rodrifarias\Blog\Domain\UseCase\User\UpdateUser;

class UpdateUserTest extends TestCase
{
    private UpdateUser $updateUser;
    private CreateUser $createUser;
    private ShowUser $showUser;

    protected function setUp(): void
    {
        $repository = new UserRepositoryInMemory();
        $this->updateUser = new UpdateUser($repository);
        $this->createUser = new CreateUser($repository);
        $this->showUser = new ShowUser($repository);
    }

    public function testShouldUpdateUser(): void
    {
        $userCreated = $this->createUser->execute(CreateUserInputData::create([
            'name' => 'Rodrigo Farias',
            'email' => 'rodrigo.farias@teste.com',
            'password' => 'qwe'
        ]));

        $this->updateUser->execute($userCreated->getId(), UpdateUserInputData::create([
            'name' => 'Rodrigo de Campos Farias',
            'email' => 'rodrigo.farias@teste.com'
        ]));

        $user = $this->showUser->execute($userCreated->getId());

        $this->assertSame('Rodrigo de Campos Farias', $user->getName());
    }

    public function testShouldGenerateUserNotFoundExceptionWhenUserNotExists(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->updateUser->execute('123', UpdateUserInputData::create([
            'name' => 'Rodrigo de Campos Farias',
            'email' => 'rodrigo.farias@teste.com'
        ]));
    }

    public function testShouldGenerateEmailExistsExceptionWhenUpdateUserWithEmailOtherUser(): void
    {
        $this->expectException(EmailExistsException::class);

        $this->createUser->execute(CreateUserInputData::create([
            'name' => 'Rodrigo Farias',
            'email' => 'rodrigo.farias@teste.com',
            'password' => 'qwe'
        ]));

        $user = $this->createUser->execute(CreateUserInputData::create([
            'name' => 'Rodrigo Farias',
            'email' => 'rodrigo.farias2@teste.com',
            'password' => 'qwe'
        ]));

        $this->updateUser->execute($user->getId(), UpdateUserInputData::create([
            'name' => 'Rodrigo de Campos Farias',
            'email' => 'rodrigo.farias@teste.com'
        ]));
    }
}
