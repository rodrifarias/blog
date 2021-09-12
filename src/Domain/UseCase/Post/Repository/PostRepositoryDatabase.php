<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;
use Rodrifarias\Blog\Domain\UseCase\Post\Exception\PostNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Infra\Database\Entity\Post;
use Rodrifarias\Blog\Infra\Database\Entity\User;

class PostRepositoryDatabase implements PostRepositoryInterface
{
    private EntityRepository $postRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->postRepository = $this->entityManager->getRepository(Post::class);
    }
    public function findAll(): array
    {
        return $this->postRepository->findAll();
    }

    public function save(UuidInterface $idPost, User $user, CreatePostInputData $createPostInputData): void
    {
        $post = new Post();
        $post->setId($idPost);
        $post->setTitle($createPostInputData->getTitle());
        $post->setContent($createPostInputData->getContent());
        $post->setCreatedAt($createPostInputData->getCreatedAt());
        $post->setUser($user);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    /**
     * @throws PostNotFoundException
     */
    public function findById(string $id): Post
    {
        $post = $this->postRepository->find($id);

        if (!$post) {
            throw new PostNotFoundException();
        }

        return $post;
    }

    public function update(string $id, string $title, string $content): void
    {
        $post = $this->findById($id);
        $post->setTitle($title);
        $post->setContent($content);

        $this->entityManager->flush();
    }

    public function delete(string $id): void
    {
        $post = $this->findById($id);

        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}
