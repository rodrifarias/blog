<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post\Repository;

use Ramsey\Uuid\UuidInterface;
use Rodrifarias\Blog\Domain\UseCase\Post\Exception\PostNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Infra\Database\Entity\Post;
use Rodrifarias\Blog\Infra\Database\Entity\User;

interface PostRepositoryInterface
{
    public function findAll(): array;
    public function save(UuidInterface $idPost, User $user, CreatePostInputData $createPostInputData): void;

    /**
     * @throws PostNotFoundException
     */
    public function findById(string $id): Post;
    public function update(string $id, string $title, string $content): void;
    public function delete(string $id): void;
}
