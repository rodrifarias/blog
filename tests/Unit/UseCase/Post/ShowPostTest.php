<?php

namespace Tests\Unit\UseCase\Post;

use DateTime;
use Rodrifarias\Blog\Domain\UseCase\Post\CreatePost;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\Post\ShowPost;

class ShowPostTest extends TestCasePost
{
    private ShowPost $showPost;
    private CreatePost $createPost;

    protected function setUp(): void
    {
        parent::setUp();
        $repository = new PostRepositoryInMemory();
        $this->showPost = new ShowPost($repository);
        $this->createPost = new CreatePost($repository, $this->userRepository);
    }

    public function testShouldShowPost(): void
    {
        $user = $this->createUser();
        $createdAt = new DateTime();

        $postInputData = CreatePostInputData::create([
            'title' => 'TITLE POST',
            'content' => 'CONTENT POST',
            'createdAt' => $createdAt
        ]);

        $postCreated = $this->createPost->execute($user->getId(), $postInputData);
        $post = $this->showPost->execute($postCreated->getIdPost());

        $this->assertIsString($post->getIdPost());
        $this->assertSame('TITLE POST', $post->getTitle());
        $this->assertSame('CONTENT POST', $post->getContent());
        $this->assertSame($createdAt->format('Y-m-d H:i:s'), $post->getCreatedAt());
    }
}
