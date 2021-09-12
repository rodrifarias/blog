<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\Repository;

use Rodrifarias\Blog\Domain\UseCase\User\Entity\User;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;
use Rodrifarias\Blog\Infra\Database\Entity\User as UserEntity;

interface UserRepositoryInterface
{
    public function save(User $userDomainEntity): void;
    public function update(string $id, string $name, ?string $email = null): void;
    public function delete(string $id): void;
    public function findByEmail(string $email): ?UserEntity;
    public function findAll(): array;

    /**
     * @throws UserNotFoundException
     */
    public function findById(string $id): UserEntity;
}
