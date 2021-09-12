<?php

namespace Rodrifarias\Blog\Domain\UseCase\User;

use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryInterface;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInterface;

class DeleteUser extends AbstractUseCaseUser
{
    public function __construct(
        UserRepositoryInterface $userRepository,
        private PostRepositoryInterface $postRepository
    ) {
        parent::__construct($userRepository);
    }

    /**
     * @throws UserNotFoundException
     */
    public function execute(string $id): void
    {
        $user = $this->userRepository->findById($id);

        foreach ($user->getPosts() as $post) {
            $this->postRepository->delete($post->getId());
        }

        $this->userRepository->delete($user->getId());
    }
}
