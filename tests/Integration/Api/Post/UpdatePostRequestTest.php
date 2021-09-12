<?php

namespace Tests\Integration\Api\Post;

use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class UpdatePostRequestTest extends TestCaseRequest
{
    protected function setUp(): void
    {
        $this->createUserDefault();
    }

    protected function tearDown(): void
    {
        $this->deleteUserDefault();
    }

    public function testShouldUpdatePost(): void
    {
        $endpoint = 'posts';

        $requestCreatePost = $this->createRequestWithAuthorization($endpoint, 'POST', [
            'title' => 'TITLE POST 1',
            'content' => 'CONTENT POST 1'
        ]);

        $endpoint .= '/' . $requestCreatePost->content->idPost;

        $this->createRequestWithAuthorization($endpoint, 'PUT', [
            'title' => 'TITLE POST 1 UPDATED',
            'content' => 'CONTENT POST 1'
        ]);

        $requestPostUpdated = $this->createRequestWithAuthorization($endpoint, 'GET');
        $post = $requestPostUpdated->content;

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
        $this->assertSame( 'TITLE POST 1 UPDATED', $post->title);
        $this->assertSame( 'CONTENT POST 1', $post->content);

        $this->createRequestWithAuthorization($endpoint, 'DELETE');
    }

    public function testShouldNotUpdatePostWhenPostNotExists(): void
    {
        $requestPostUpdated = $this->createRequestWithAuthorization('posts/abc-123', 'PUT', [
            'title' => 'TITLE POST 1',
            'content' => 'CONTENT POST 1'
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_NOT_FOUND, $requestPostUpdated->statusCode);
    }

    public function testShouldNotUpdatePostWhenUserNotExists(): void
    {
        $header = ['Authorization' => 'Bearer ' . $this->createTokenUnknownUser()];
        $jsonParams = ['title' => 'TITLE POST 1', 'content' => 'CONTENT POST 1'];
        $requestPostUpdated = $this->createRequest('posts/abc-123', 'PUT', $jsonParams, $header);

        $this->assertSame( StatusCodeInterface::STATUS_NOT_FOUND, $requestPostUpdated->statusCode);
    }

    public function testShouldNotUpdatePostWhenUserIsNotOwnerPost(): void
    {
        $jsonParams = [
            'title' => 'TITLE POST 99',
            'content' => 'CONTENT POST 99'
        ];

        $requestCreatePost = $this->createRequestWithAuthorization('posts', 'POST', $jsonParams);

        $requestPostUpdated = $this->createRequest('posts/' . $requestCreatePost->content->idPost, 'PUT', $jsonParams, headers: [
            'Authorization' => 'Bearer ' . $this->createTokenNewUser()
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_BAD_REQUEST, $requestPostUpdated->statusCode);
        $this->assertSame( 'Only the post owner can do action', $requestPostUpdated->content->description);
        $this->createRequestWithAuthorization('posts/' . $requestCreatePost->content->idPost, 'DELETE');
        $this->deleteNewUser();
    }

    public function testShouldNotUpdatePostWithoutAuthorization(): void
    {
        $requestPostUpdated = $this->createRequest('posts/abc123', 'PUT');
        $this->assertSame( StatusCodeInterface::STATUS_UNAUTHORIZED, $requestPostUpdated->statusCode);
    }
}