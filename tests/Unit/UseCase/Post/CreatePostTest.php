<?php

namespace Tests\Unit\UseCase\Post;

use DateTime;
use Rodrifarias\Blog\Domain\UseCase\Post\CreatePost;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\User\Exception\UserNotFoundException;

class CreatePostTest extends TestCasePost
{
    private CreatePost $createPost;

    protected function setUp(): void
    {
        parent::setUp();

        $postRepository = new PostRepositoryInMemory();
        $this->createPost = new CreatePost($postRepository, $this->userRepository);
    }

    public function testShouldCreatePost(): void
    {
        $user = $this->createUser();
        $createdAt = new DateTime();

        $postInputData = CreatePostInputData::create([
            'title' => 'TITLE POST',
            'content' => 'CONTENT POST',
            'createdAt' => $createdAt
        ]);

        $post = $this->createPost->execute($user->getId(), $postInputData);
        $this->assertIsString($post->getIdPost());
        $this->assertSame('CONTENT POST', $post->getContent());
        $this->assertSame('CONTENT POST', $post->getContent());
        $this->assertSame($createdAt->format('Y-m-d H:i:s'), $post->getCreatedAt());
    }

    public function testShouldNotCreatePostWhenUserNotExists(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->createPost->execute('abc', CreatePostInputData::create([
            'title' => 'TITLE POST',
            'content' => 'CONTENT POST',
            'createdAt' => new DateTime()
        ]));
    }
}
