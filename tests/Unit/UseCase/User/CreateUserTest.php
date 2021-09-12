<?php

namespace Tests\Unit\UseCase\User;

use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Domain\Exception\InvalidEmailException;
use Rodrifarias\Blog\Domain\Exception\InvalidNameException;
use Rodrifarias\Blog\Domain\UseCase\User\CreateUser;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserExistsException;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UserOutputData;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInMemory;

class CreateUserTest extends TestCase
{
    private CreateUser $createUser;

    protected function setUp(): void
    {
        $repository = new UserRepositoryInMemory();
        $this->createUser = new CreateUser($repository);
    }

    public function testShouldCreateUser(): void
    {
        $user = $this->createUser->execute(CreateUserInputData::create([
            'name' => 'ROdrigo Farias',
            'email' => 'rodrigo.farias@teste.com',
            'password' => 'qwe'
        ]));

        $this->assertInstanceOf(UserOutputData::class, $user);
        $this->assertEquals($user->getId(), $user->getId());
        $this->assertEquals($user->getName(), $user->getName());
        $this->assertEquals($user->getEmail(), $user->getEmail());
    }

    public function testGenerateUserExistsExceptionWhenCreateUserWithSomeEmail(): void
    {
        $this->expectException(UserExistsException::class);
        $this->expectErrorMessage('User already exists');

        $createUserInputData = CreateUserInputData::create([
            'name' => 'ROdrigo Farias',
            'email' => 'rodrigo.farias@teste.com',
            'password' => 'qwe'
        ]);

        $this->createUser->execute($createUserInputData);
        $this->createUser->execute($createUserInputData);
    }

    public function testGenerateInvalidEmailExceptionWhenCreateUserWithInvalidEmail(): void
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectErrorMessage('Invalid email');

        $createUserInputData = CreateUserInputData::create([
            'name' => 'ROdrigo Farias',
            'email' => 'rodrigo',
            'password' => 'qwe'
        ]);

        $this->createUser->execute($createUserInputData);
    }

    public function testGenerateInvalidNameExceptionWhenCreateUserWithInvalidName(): void
    {
        $this->expectException(InvalidNameException::class);
        $this->expectErrorMessage('Invalid name');

        $createUserInputData = CreateUserInputData::create([
            'name' => 'ROdrigo',
            'email' => 'rodrigo.farias@teste.com',
            'password' => 'qwe'
        ]);

        $this->createUser->execute($createUserInputData);
    }
}
