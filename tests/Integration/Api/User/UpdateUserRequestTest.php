<?php

namespace Tests\Integration\Api\User;

use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class UpdateUserRequestTest extends TestCaseRequest
{
    protected function tearDown(): void
    {
        $this->deleteUserDefault();
    }

    public function testShouldUpdateUserRequest(): void
    {
        $request = $this->createUserDefault();
        $name = 'User Updated';
        $path = 'users/' . $request->content->id;

        $updateUserRequest = $this->createRequestWithAuthorization($path, 'PUT', [
            'name' => $name,
            'email' => $request->content->email
        ]);

        $this->assertSame(StatusCodeInterface::STATUS_NO_CONTENT, $updateUserRequest->statusCode);

        $showUserRequest = $this->createRequestWithAuthorization($path, 'GET');

        $this->assertSame(StatusCodeInterface::STATUS_OK, $showUserRequest->statusCode);
        $this->assertSame($name, $showUserRequest->content->name);
    }

    public function testShouldUNotUpdateUserWhenUserNotFoundRequest(): void
    {
        $this->createUserDefault();

        $updateUserRequest = $this->createRequestWithAuthorization('users/abc-123', 'PUT', [
            'name' => 'User Updated',
            'email' => 'email@email.com'
        ]);

        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $updateUserRequest->statusCode);
        $this->assertSame('User not found', $updateUserRequest->content->description);
    }

    public function testShouldNotUpdateUsersWithoutAuthorizationRequest(): void
    {
        $updateUserRequest = $this->createRequest('users/123-abc', 'GET');
        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $updateUserRequest->statusCode);
        $this->assertSame('Unauthorized', $updateUserRequest->content->message);
    }
}