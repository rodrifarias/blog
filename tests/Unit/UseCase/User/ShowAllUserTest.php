<?php

namespace Tests\Unit\UseCase\User;

use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Domain\UseCase\User\CreateUser;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\User\ShowAllUser;

class ShowAllUserTest extends TestCase
{
    private ShowAllUser $showAllUser;
    private CreateUser $createUser;

    protected function setUp(): void
    {
        $repository = new UserRepositoryInMemory();
        $this->showAllUser = new ShowAllUser($repository);
        $this->createUser = new CreateUser($repository);
    }

    public function testShouldShowAllUser(): void
    {
        $this->assertCount(0, $this->showAllUser->execute()->users());

        $user1 = CreateUserInputData::create([
            'name' => 'ROdrigo Farias',
            'email' => 'rodrigo.farias@teste.com',
            'password' => '123'
        ]);

        $user2 = CreateUserInputData::create([
            'name' => 'Leo Farias',
            'email' => 'leo.farias@teste.com',
            'password' => '123'
        ]);

        $this->createUser->execute($user1);
        $this->createUser->execute($user2);

        $users = $this->showAllUser->execute()->users();

        $this->assertCount(2, $users);
        $this->assertSame('Leo Farias', $users[1]->getName());
        $this->assertSame('leo.farias@teste.com', $users[1]->getEmail());
        $this->assertIsString($users[1]->getId());
    }
}
