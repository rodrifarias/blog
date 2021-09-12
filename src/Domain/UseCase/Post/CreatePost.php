<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post;

use Ramsey\Uuid\Uuid;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\PostOutputData;
use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryInterface;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInterface;

class CreatePost extends AbstractUseCasePost
{
    public function __construct(
        PostRepositoryInterface $postRepository,
        private UserRepositoryInterface $userRepository
    ) {
        parent::__construct($postRepository);
    }

    /**
     * @throws UserNotFoundException
     */
    public function execute(string $userId, CreatePostInputData $createPostInputData): PostOutputData
    {
        $user = $this->userRepository->findById($userId);
        $idPost = Uuid::uuid4();
        $this->postRepository->save($idPost, $user, $createPostInputData);

        return new PostOutputData(
            $idPost,
            $user->getId(),
            $user->getName(),
            $createPostInputData->getTitle(),
            $createPostInputData->getContent(),
            $createPostInputData->getCreatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
