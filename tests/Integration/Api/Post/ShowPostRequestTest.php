<?php

namespace Tests\Integration\Api\Post;

use DateTime;
use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class ShowPostRequestTest extends TestCaseRequest
{
    protected function setUp(): void
    {
        $this->createUserDefault();
    }

    protected function tearDown(): void
    {
        $this->deleteUserDefault();
    }

    public function testShouldShowPostRequest(): void
    {
        $now = new DateTime();
        $title = 'TITLE CREATED NOW ' . $now->getTimestamp();

        $requestCreatePost = $this->createRequestWithAuthorization('posts', 'POST', [
            'title' => $title,
            'content' => 'CONTENT POST 1'
        ]);

        $requestShowPost = $this->createRequest('posts/' . $requestCreatePost->content->idPost, 'GET');
        $content = $requestShowPost->content;

        $this->assertSame( StatusCodeInterface::STATUS_OK, $requestShowPost->statusCode);

        $post = $content;

        $validAttributes = ['idPost', 'idUser', 'userName', 'title', 'content', 'createdAt'];
        $diffAttributes = array_diff(array_keys((array)$post), $validAttributes);

        $this->assertEmpty($diffAttributes);
        $this->assertObjectHasAttribute( 'idPost', $post);
        $this->assertObjectHasAttribute( 'idUser', $post);
        $this->assertObjectHasAttribute( 'title', $post);
        $this->assertObjectHasAttribute( 'content', $post);
        $this->assertObjectHasAttribute( 'userName', $post);
        $this->assertObjectHasAttribute( 'createdAt', $post);
        $this->assertIsString($post->idPost);
        $this->assertIsString($post->idUser);
        $this->assertIsString($post->userName);
        $this->assertIsString($post->title);
        $this->assertIsString($post->content);
        $this->assertIsString($post->createdAt);
        $this->assertSame( $title, $post->title);
        $this->assertSame( 'CONTENT POST 1', $post->content);

        $this->createRequestWithAuthorization('posts/' . $requestCreatePost->content->idPost, 'DELETE');
    }

    public function testShouldNotShowPostWhenPostNotExistsRequest(): void
    {
        $requestShowPost = $this->createRequest('posts/123-abc', 'GET');
        $this->assertSame( StatusCodeInterface::STATUS_NOT_FOUND, $requestShowPost->statusCode);
        $this->assertSame( 'Post not found', $requestShowPost->content->description);
    }
}