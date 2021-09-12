<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post\Repository;

use Ramsey\Uuid\UuidInterface;
use Rodrifarias\Blog\Domain\UseCase\Post\Exception\PostNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Infra\Database\Entity\Post;
use Rodrifarias\Blog\Infra\Database\Entity\User;

class PostRepositoryInMemory implements PostRepositoryInterface
{
    /** @var Post[] */
    private array $posts = [];

    /**
     * @return Post[]
     */
    public function findAll(): array
    {
        return $this->posts;
    }

    public function findById(string $id): Post
    {
        $filterPost = array_filter($this->posts, fn (Post $post) => $post->getId() === $id);

        if (!$filterPost) {
            throw new PostNotFoundException();
        }

        return array_shift($filterPost);
    }

    public function save(UuidInterface $idPost, User $user, CreatePostInputData $createPostInputData): void
    {
        $post = new Post();
        $post->setId($idPost);
        $post->setUser($user);
        $post->setContent($createPostInputData->getContent());
        $post->setTitle($createPostInputData->getTitle());
        $post->setCreatedAt($createPostInputData->getCreatedAt());

        $this->posts[] = $post;
    }

    public function update(string $id, string $title, string $content): void
    {
        $filterPost = array_filter($this->posts, fn (Post $post) => $post->getId() === $id);
        $indexPost = array_key_first($filterPost);
        $post = $filterPost[$indexPost];
        $post->setTitle($title);
        $post->setContent($content);
        $this->posts[$indexPost] = $post;
    }

    public function delete(string $id): void
    {
        $filterPost = array_filter($this->posts, fn (Post $post) => $post->getId() === $id);
        $indexPost = array_key_first($filterPost);
        unset($this->posts[$indexPost]);
    }
}
