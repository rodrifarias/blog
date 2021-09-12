<?php

namespace Tests\Unit\UseCase\Auth;

use DateInterval;
use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Application\Service\Jwt\UserJWTService;
use Rodrifarias\Blog\Domain\UseCase\Auth\AuthenticateUser;
use Rodrifarias\Blog\Domain\UseCase\Auth\CreateCredentialsUser;
use Rodrifarias\Blog\Domain\UseCase\Auth\Exception\InvalidUsernamePasswordException;
use Rodrifarias\Blog\Domain\UseCase\Auth\InputOutputData\AuthUserCredentialsInputData;
use Rodrifarias\Blog\Domain\UseCase\Auth\InputOutputData\UserCredentialsOutputData;
use Rodrifarias\Blog\Domain\UseCase\User\CreateUser;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInMemory;

class AuthenticateUserTest extends TestCase
{
    private AuthenticateUser $authenticateUser;
    private CreateUser $createUser;
    private CreateCredentialsUser $createCredentialsUser;

    protected function setUp(): void
    {
        $repository = new UserRepositoryInMemory();
        $this->authenticateUser = new AuthenticateUser($repository);
        $this->createCredentialsUser = new CreateCredentialsUser($repository, new UserJWTService('abc', 'HS256'));
        $this->createUser = new CreateUser($repository);
    }

    public function testShouldAuthenticateUser(): void
    {
        $inputData = [
            'name' => 'Rodrigo Farias',
            'email' => 'rodrigo.farias@teste.com',
            'password' => '123'
        ];

        $userInputData = CreateUserInputData::create($inputData);
        $this->createUser->execute($userInputData);

        $credentials = AuthUserCredentialsInputData::create($inputData);
        $this->authenticateUser->execute($credentials);
        $now = new \DateTime();

        $credentials = $this->createCredentialsUser->execute($userInputData->getEmail(), $now->add(new DateInterval('P1D')));

        $this->assertInstanceOf(UserCredentialsOutputData::class, $credentials);
        $this->assertSame('rodrigo.farias@teste.com', $credentials->getEmail());
        $this->assertSame('Rodrigo Farias', $credentials->getName());
        $this->assertIsString($credentials->getToken());
    }

    public function testShouldNotAuthenticateUserWhenIsNotValidEmail(): void
    {
        $this->expectException(InvalidUsernamePasswordException::class);

        $userInputData = CreateUserInputData::create([
            'name' => 'Rodrigo Farias',
            'email' => 'rodrigo.farias@teste.com',
            'password' => '123'
        ]);

        $this->createUser->execute($userInputData);

        $credentials = AuthUserCredentialsInputData::create([
            'email' => 'rodrigo.farias@teste.com.br',
            'password' => '123'
        ]);

        $this->authenticateUser->execute($credentials);
    }

    public function testShouldNotAuthenticateUserWhenIncorrectPassword(): void
    {
        $this->expectException(InvalidUsernamePasswordException::class);

        $userInputData = CreateUserInputData::create([
            'name' => 'Rodrigo Farias',
            'email' => 'rodrigo.farias@teste.com',
            'password' => '123'
        ]);

        $this->createUser->execute($userInputData);

        $credentials = AuthUserCredentialsInputData::create([
            'email' => 'rodrigo.farias@teste.com',
            'password' => '12'
        ]);

        $this->authenticateUser->execute($credentials);
    }
}
