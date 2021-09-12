<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post\Facade;

use Exception;
use Rodrifarias\Blog\Domain\UseCase\Post\CreatePost;
use Rodrifarias\Blog\Domain\UseCase\Post\DeletePost;
use Rodrifarias\Blog\Domain\UseCase\Post\Exception\OnlyPostOwnerCanDoActionException;
use Rodrifarias\Blog\Domain\UseCase\Post\Exception\PostNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\PostOutputData;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\PostOutputDataCollection;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\UpdatePostInputData;
use Rodrifarias\Blog\Domain\UseCase\Post\ShowAllPost;
use Rodrifarias\Blog\Domain\UseCase\Post\ShowPost;
use Rodrifarias\Blog\Domain\UseCase\Post\UpdatePost;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;

class PostActionFacade
{
    public function __construct(
        private ShowPost $showPost,
        private ShowAllPost $showAllPost,
        private CreatePost $createPost,
        private UpdatePost $updatePost,
        private DeletePost $deletePost,
    ) {
    }

    /**
     * @throws Exception
     * @throws PostNotFoundException
     */
    public function show(string $id): PostOutputData
    {
        return $this->showPost->execute($id);
    }

    public function showAll(): PostOutputDataCollection
    {
        return $this->showAllPost->execute();
    }

    /**
     * @throws UserNotFoundException
     */
    public function create(string $userId, CreatePostInputData $createPostInputData): PostOutputData
    {
        return $this->createPost->execute($userId, $createPostInputData);
    }

    /**
     * @throws PostNotFoundException
     * @throws UserNotFoundException
     * @throws OnlyPostOwnerCanDoActionException
     */
    public function update(string $idPost, string $idUser, UpdatePostInputData $updatePostInputData): void
    {
        $this->updatePost->execute($idPost, $idUser, $updatePostInputData);
    }

    /**
     * @throws PostNotFoundException
     * @throws UserNotFoundException
     * @throws OnlyPostOwnerCanDoActionException
     */
    public function delete(string $idPost, string $idUser): void
    {
        $this->deletePost->execute($idPost, $idUser);
    }
}
