<?php

namespace Tests\Unit\UseCase\Post;

use DateTime;
use Rodrifarias\Blog\Domain\UseCase\Post\CreatePost;
use Rodrifarias\Blog\Domain\UseCase\Post\Exception\OnlyPostOwnerCanDoActionException;
use Rodrifarias\Blog\Domain\UseCase\Post\Exception\PostNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\UpdatePostInputData;
use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\Post\ShowPost;
use Rodrifarias\Blog\Domain\UseCase\Post\UpdatePost;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;

class UpdatePostTest extends TestCasePost
{
    private CreatePost $createPost;
    private UpdatePost $updatePost;
    private ShowPost $showPost;

    protected function setUp(): void
    {
        parent::setUp();

        $postRepository = new PostRepositoryInMemory();
        $this->createPost = new CreatePost($postRepository, $this->userRepository);
        $this->updatePost = new UpdatePost($postRepository, $this->userRepository);
        $this->showPost = new ShowPost($postRepository);
    }

    public function testShouldUpdatePost(): void
    {
        $user = $this->createUser();

        $postInputData = CreatePostInputData::create([
            'title' => 'TITLE POST',
            'content' => 'CONTENT POST',
            'createdAt' => new DateTime()
        ]);

        $postCreated = $this->createPost->execute($user->getId(), $postInputData);

        $updateInputData = UpdatePostInputData::create([
            'title' => 'Updated title',
            'content' => 'Updated content',
        ]);

        $this->updatePost->execute($postCreated->getIdPost(), $user->getId(), $updateInputData);

        $postUpdated = $this->showPost->execute($postCreated->getIdPost());

        $this->assertSame('Updated title', $postUpdated->getTitle());
        $this->assertSame('Updated content', $postUpdated->getContent());
    }

    public function testShouldNotUpdatePostWhenPostNotExists(): void
    {
        $this->expectException(PostNotFoundException::class);

        $this->updatePost->execute('abc-123', 'abc', UpdatePostInputData::create([
            'title' => 'Updated title',
            'content' => 'Updated content',
        ]));
    }

    public function testShouldNotUpdatePostWhenUserNotExists(): void
    {
        $this->expectException(UserNotFoundException::class);

        $user = $this->createUser();

        $postInputData = CreatePostInputData::create([
            'title' => 'TITLE POST',
            'content' => 'CONTENT POST',
            'createdAt' => new DateTime()
        ]);

        $postCreated = $this->createPost->execute($user->getId(), $postInputData);

        $this->updatePost->execute($postCreated->getIdPost(), 'abc', UpdatePostInputData::create([
            'title' => 'Updated title',
            'content' => 'Updated content',
        ]));
    }

    public function testShouldNotUpdatePostWhenUserIsNotOwnerPost(): void
    {
        $this->expectException(OnlyPostOwnerCanDoActionException::class);

        $user1 = $this->createUser();
        $user2 = $this->createUser('teste@teste.com');

        $postInputData = CreatePostInputData::create([
            'title' => 'TITLE POST',
            'content' => 'CONTENT POST',
            'createdAt' => new DateTime()
        ]);

        $postCreated = $this->createPost->execute($user1->getId(), $postInputData);

        $this->updatePost->execute($postCreated->getIdPost(), $user2->getId(), UpdatePostInputData::create([
            'title' => 'Updated title',
            'content' => 'Updated content',
        ]));
    }
}
