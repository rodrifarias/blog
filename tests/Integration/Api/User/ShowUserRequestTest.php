<?php

namespace Tests\Integration\Api\User;

use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class ShowUserRequestTest extends TestCaseRequest
{
    protected function tearDown(): void
    {
        $this->deleteUserDefault();
    }

    public function testShouldShowUserRequest(): void
    {
        $user = $this->createUserDefault();
        $showUserRequest = $this->createRequestWithAuthorization('users/' . $user->content->id, 'GET');

        $this->assertSame(StatusCodeInterface::STATUS_OK, $showUserRequest->statusCode);
        $this->assertSame($user->content->name, $showUserRequest->content->name);
        $this->assertSame($user->content->email, $showUserRequest->content->email);
        $this->assertSame($user->content->id, $showUserRequest->content->id);
    }

    public function testShouldNotShowUserWhenUserNotFoundRequest(): void
    {
        $this->createUserDefault();
        $showUserRequest = $this->createRequestWithAuthorization('users/123-abc', 'GET');
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $showUserRequest->statusCode);
        $this->assertSame('User not found', $showUserRequest->content->description);
    }

    public function testShouldNotShowUsersWithoutAuthorizationRequest(): void
    {
        $showUserRequest = $this->createRequest('users/123-abc', 'GET');
        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $showUserRequest->statusCode);
        $this->assertSame('Unauthorized', $showUserRequest->content->message);
    }
}