<?php

namespace Tests\Integration\Api\Post;

use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class CreatePostRequestTest extends TestCaseRequest
{
    protected function setUp(): void
    {
        $this->createUserDefault();
    }

    protected function tearDown(): void
    {
        $this->deleteUserDefault();
    }

    public function testShouldCreatePostRequest(): void
    {
        $requestCreatePost = $this->createRequestWithAuthorization('posts', 'POST', [
            'title' => 'TITLE POST 1',
            'content' => 'CONTENT POST 1'
        ]);

        $post = $requestCreatePost->content;

        $validAttributes = ['idPost', 'idUser', 'userName', 'title', 'content', 'createdAt'];
        $diffAttributes = array_diff(array_keys((array)$post), $validAttributes);

        $this->assertEmpty($diffAttributes);
        $this->assertSame( StatusCodeInterface::STATUS_CREATED, $requestCreatePost->statusCode);
        $this->assertObjectHasAttribute( 'idPost', $post);
        $this->assertObjectHasAttribute( 'idUser', $post);
        $this->assertObjectHasAttribute( 'title', $post);
        $this->assertObjectHasAttribute( 'content', $post);
        $this->assertObjectHasAttribute( 'createdAt', $post);
        $this->assertIsString($post->idPost);
        $this->assertIsString($post->idUser);
        $this->assertIsString($post->title);
        $this->assertIsString($post->content);
        $this->assertIsString($post->createdAt);

        $this->createRequestWithAuthorization('posts/' . $requestCreatePost->content->idPost, 'DELETE');
    }

    public function testShouldNotCreatePostWithoutAuthorizationRequest(): void
    {
        $requestCreatePost = $this->createRequest('posts', 'POST',[
            'title' => 'TITLE POST 1', 'content' => 'CONTENT POST 1'
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_UNAUTHORIZED, $requestCreatePost->statusCode);
    }

    public function testShouldNotCreatePostWhenUserNotExistsRequest(): void
    {
        $requestCreatePost = $this->createRequest('posts', 'POST',
            ['title' => 'TITLE POST 1', 'content' => 'CONTENT POST 1'],
            ['Authorization' => 'Bearer ' . $this->createTokenUnknownUser()]
        );

        $this->assertSame( StatusCodeInterface::STATUS_NOT_FOUND, $requestCreatePost->statusCode);
    }
}
