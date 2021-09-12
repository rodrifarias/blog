<?php

namespace Tests\Unit\Service;

use DateTime;
use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Application\Service\LogApp\CreateLogAppInterface;
use Rodrifarias\Blog\Application\Service\LogApp\CreateLogAppService;
use Rodrifarias\Blog\Application\Service\LogApp\Repository\LogAppRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\User\CreateUser;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInMemory;

class CreateLogAppServiceTest extends TestCase
{
    private CreateLogAppInterface $createLogApp;
    private CreateUser $createUser;

    protected function setUp(): void
    {
        $userRepository = new UserRepositoryInMemory();
        $logAppRepository = new LogAppRepositoryInMemory();
        $this->createLogApp = new CreateLogAppService($logAppRepository, $userRepository);
        $this->createUser = new CreateUser($userRepository);
    }

    public function testShouldCreateLogAppWithUser(): void
    {
        $this->expectNotToPerformAssertions();

        $user = $this->createUser->execute(CreateUserInputData::create([
            'name' => 'Rodrigo Farias',
            'email' => 'rodrigo.farias@teste.com',
            'password' => 'qwe'
        ]));

        $this->createLogApp->execute('POST', '/teste/123', new DateTime(), null, $user->getId());
    }

    public function testShouldCreateLogAppWithoutUser(): void
    {
        $this->expectNotToPerformAssertions();
        $this->createLogApp->execute('POST', '/teste/123', new DateTime(), null, null);
    }
}
