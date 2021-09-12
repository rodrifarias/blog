<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\Repository;

use Rodrifarias\Blog\Domain\UseCase\User\Entity\User;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;
use Rodrifarias\Blog\Infra\Database\Entity\User as UserEntity;

class UserRepositoryInMemory implements UserRepositoryInterface
{
    /** @var UserEntity[] */
    private array $users = [];

    public function save(User $userDomainEntity): void
    {
        $user = new UserEntity();
        $user->setId($userDomainEntity->id());
        $user->setName($userDomainEntity->name());
        $user->setEmail($userDomainEntity->email());
        $user->setPassword($userDomainEntity->password());

        $this->users[] = $user;
    }

    public function findByEmail(string $email): ?UserEntity
    {
        $user = array_filter($this->users, fn (UserEntity $u) => $u->getEmail() === $email);
        return $user ? array_shift($user) : null;
    }

    /**
     * @throws UserNotFoundException
     */
    public function findById(string $id): UserEntity
    {
        $user = array_filter($this->users, fn (UserEntity $u) => $u->getId() === $id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return array_shift($user);
    }

    public function update(string $id, string $name, ?string $email = null): void
    {
        $users = array_filter($this->users, fn (UserEntity $u) => $u->getId() === $id);
        $index = array_key_first($users);
        $user = array_shift($users);

        $user->setName($name);

        if ($email) {
            $user->setEmail($email);
        }

        $this->users[$index] = $user;
    }

    /**
     * @return UserEntity[]
     */
    public function findAll(): array
    {
        return $this->users;
    }

    public function delete(string $id): void
    {
        $users = array_filter($this->users, fn (UserEntity $u) => $u->getId() === $id);
        $index = array_key_first($users);
        unset($this->users[$index]);
    }
}
