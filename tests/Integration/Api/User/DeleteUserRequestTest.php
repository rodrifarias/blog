<?php

namespace Tests\Integration\Api\User;

use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class DeleteUserRequestTest extends TestCaseRequest
{
    protected function tearDown(): void
    {
        $this->deleteUserDefault();
    }

    public function testShouldDeleteUserRequest(): void
    {
        $request = $this->createUserDefault();
        $deleteUserRequest = $this->createRequestWithAuthorization('users/' . $request->content->id, 'DELETE');
        $this->assertSame(StatusCodeInterface::STATUS_NO_CONTENT, $deleteUserRequest->statusCode);
    }

    public function testShouldNotDeleteUserWhenUserNotFoundRequest(): void
    {
        $this->createUserDefault();
        $updateUserRequest = $this->createRequestWithAuthorization('users/abc-123', 'DELETE');
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $updateUserRequest->statusCode);
        $this->assertSame('User not found', $updateUserRequest->content->description);
    }

    public function testShouldNotDeleteUserWithoutAuthorizationRequest(): void
    {
        $updateUserRequest = $this->createRequest('users/abc-123', 'DELETE');
        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $updateUserRequest->statusCode);
        $this->assertSame('Unauthorized', $updateUserRequest->content->message);
    }
}
