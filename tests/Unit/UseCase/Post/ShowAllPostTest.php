<?php

namespace Tests\Unit\UseCase\Post;

use DateTime;
use Rodrifarias\Blog\Domain\UseCase\Post\CreatePost;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryInMemory;
use Rodrifarias\Blog\Domain\UseCase\Post\ShowAllPost;

class ShowAllPostTest extends TestCasePost
{
    private ShowAllPost $showAllPost;
    private CreatePost $createPost;

    protected function setUp(): void
    {
        parent::setUp();

        $postRepository = new PostRepositoryInMemory();
        $this->showAllPost = new ShowAllPost($postRepository);
        $this->createPost = new CreatePost($postRepository, $this->userRepository);
    }

    public function testShouldShowAllPost(): void
    {
        $this->assertCount(0, $this->showAllPost->execute()->getPosts());
        $user = $this->createUser();
        $createdAt = new DateTime();

        $postInputData = CreatePostInputData::create([
            'title' => 'TITLE POST',
            'content' => 'CONTENT POST',
            'createdAt' => $createdAt
        ]);

        $this->createPost->execute($user->getId(), $postInputData);
        $posts = $this->showAllPost->execute()->getPosts();
        $this->assertCount(1, $posts);
        $post = $posts[0];
        $this->assertIsString($post->getIdPost());
        $this->assertSame('TITLE POST', $post->getTitle());
        $this->assertSame('CONTENT POST', $post->getContent());
        $this->assertSame($createdAt->format('Y-m-d H:i:s'), $post->getCreatedAt());
    }
}
