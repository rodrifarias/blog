<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post;

use Rodrifarias\Blog\Domain\UseCase\Post\Exception\OnlyPostOwnerCanDoActionException;
use Rodrifarias\Blog\Domain\UseCase\Post\Exception\PostNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryInterface;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInterface;

class DeletePost extends AbstractUseCasePost
{
    public function __construct(
        PostRepositoryInterface $postRepository,
        private UserRepositoryInterface $userRepository
    ) {
        parent::__construct($postRepository);
    }

    /**
     * @throws PostNotFoundException
     * @throws UserNotFoundException
     * @throws OnlyPostOwnerCanDoActionException
     */
    public function execute(string $idPost, string $idUser): void
    {
        $post = $this->postRepository->findById($idPost);
        $user = $this->userRepository->findById($idUser);

        if ($post->getUser()->getId() !== $user->getId()) {
            throw new OnlyPostOwnerCanDoActionException();
        }

        $this->postRepository->delete($post->getId());
    }
}
