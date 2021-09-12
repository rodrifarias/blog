<?php

namespace Tests\Unit\UseCase\Post;

use DateTime;
use Rodrifarias\Blog\Domain\UseCase\Post\CreatePost;
use Rodrifarias\Blog\Domain\UseCase\Post\DeletePost;
use Rodrifarias\Blog\Domain\UseCase\Post\Exception\OnlyPostOwnerCanDoActionException;
use Rodrifarias\Blog\Domain\UseCase\Post\Exception\PostNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\Post\ShowAllPost;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;

class DeletePostTest extends TestCasePost
{
    private DeletePost $deletePost;
    private CreatePost $createPost;
    private ShowAllPost $showAllPost;

    protected function setUp(): void
    {
        parent::setUp();

        $postRepository = new PostRepositoryInMemory();
        $this->deletePost = new DeletePost($postRepository, $this->userRepository);
        $this->createPost = new CreatePost($postRepository, $this->userRepository);
        $this->showAllPost = new ShowAllPost($postRepository);
    }

    public function testShouldDeletePost(): void
    {
        $user = $this->createUser();

        $postInputData = CreatePostInputData::create([
            'title' => 'TITLE POST',
            'content' => 'CONTENT POST',
            'createdAt' => new DateTime()
        ]);

        $postCreated = $this->createPost->execute($user->getId(), $postInputData);
        $this->assertCount(1, $this->showAllPost->execute()->getPosts());
        $this->deletePost->execute($postCreated->getIdPost(), $user->getId());
        $this->assertCount(0, $this->showAllPost->execute()->getPosts());
    }

    public function testShouldNotDeletePostWhenPostNotExists(): void
    {
        $this->expectException(PostNotFoundException::class);
        $this->deletePost->execute('asd', '123-abc');
    }

    public function testShouldNotDeletePostWhenUserNotExists(): void
    {
        $this->expectException(UserNotFoundException::class);

        $user = $this->createUser();

        $postInputData = CreatePostInputData::create([
            'title' => 'TITLE POST',
            'content' => 'CONTENT POST',
            'createdAt' => new DateTime()
        ]);

        $postCreated = $this->createPost->execute($user->getId(), $postInputData);
        $this->deletePost->execute($postCreated->getIdPost(), '123-abc');
    }

    public function testShouldNotDeletePostWhenUserIsNotOwnerPost(): void
    {
        $this->expectException(OnlyPostOwnerCanDoActionException::class);

        $user = $this->createUser();
        $user2 = $this->createUser('teste@teste.com');

        $postInputData = CreatePostInputData::create([
            'title' => 'TITLE POST',
            'content' => 'CONTENT POST',
            'createdAt' => new DateTime()
        ]);

        $postCreated = $this->createPost->execute($user->getId(), $postInputData);
        $this->deletePost->execute($postCreated->getIdPost(), $user2->getId());
    }
}
