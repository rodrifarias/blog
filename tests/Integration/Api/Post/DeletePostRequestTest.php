<?php

namespace Tests\Integration\Api\Post;

use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class DeletePostRequestTest extends TestCaseRequest
{
    protected function setUp(): void
    {
        $this->createUserDefault();
    }

    protected function tearDown(): void
    {
        $this->deleteUserDefault();
    }

    public function testShouldDeletePost(): void
    {
        $requestCreatePost = $this->createRequestWithAuthorization('posts', 'POST', [
            'title' => 'TITLE POST 1',
            'content' => 'CONTENT POST 1'
        ]);

        $deletePostRequest = $this->createRequestWithAuthorization('posts/' . $requestCreatePost->content->idPost, 'DELETE');
        $this->assertSame( StatusCodeInterface::STATUS_NO_CONTENT, $deletePostRequest->statusCode);
    }

    public function testShouldNotDeletePostWhenPostNotExists(): void
    {
        $requestPostUpdated = $this->createRequestWithAuthorization('posts/abc-123', 'DELETE');
        $this->assertSame( StatusCodeInterface::STATUS_NOT_FOUND, $requestPostUpdated->statusCode);
    }

    public function testShouldNotDeletePostWhenUserNotExists(): void
    {
        $requestPostUpdated = $this->createRequest(path: 'posts/abc-123', method: 'DELETE', headers: [
            'Authorization' => 'Bearer ' . $this->createTokenUnknownUser()
        ]);

        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $requestPostUpdated->statusCode);
    }

    public function testShouldNotUpdatePostWhenUserIsNotOwnerPost(): void
    {
        $jsonParams = [
            'title' => 'TITLE POST 99',
            'content' => 'CONTENT POST 99'
        ];

        $requestCreatePost = $this->createRequestWithAuthorization('posts', 'POST', $jsonParams);

        $requestPostUpdated = $this->createRequest(path: 'posts/' . $requestCreatePost->content->idPost, method: 'DELETE', headers: [
            'Authorization' => 'Bearer ' . $this->createTokenNewUser()
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_BAD_REQUEST, $requestPostUpdated->statusCode);
        $this->assertSame( 'Only the post owner can do action', $requestPostUpdated->content->description);
        $this->createRequestWithAuthorization('posts/' . $requestCreatePost->content->idPost, 'DELETE');
        $this->deleteNewUser();
    }

    public function testShouldNotDeletePostWithoutAuthorization(): void
    {
        $requestPostUpdated = $this->createRequest('posts/abc123', 'DELETE');
        $this->assertSame( StatusCodeInterface::STATUS_UNAUTHORIZED, $requestPostUpdated->statusCode);
    }

}