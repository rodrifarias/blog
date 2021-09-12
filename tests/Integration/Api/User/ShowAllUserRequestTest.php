<?php

namespace Tests\Integration\Api\User;

use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class ShowAllUserRequestTest extends TestCaseRequest
{
    protected function setUp(): void
    {
        $this->createUserDefault();
    }

    protected function tearDown(): void
    {
        $this->deleteUserDefault();
    }

    public function testShouldShowAllUsersRequest(): void
    {
        $usersRequest = $this->createRequestWithAuthorization('users', 'GET');
        $this->assertSame(StatusCodeInterface::STATUS_OK, $usersRequest->statusCode);
        $this->assertIsArray($usersRequest->content);

        if ($usersRequest->content) {
            $user = $usersRequest->content[0];
            $this->assertIsString($user->id);
            $this->assertIsString($user->email);
            $this->assertIsString($user->name);
        }
    }

    public function testShouldNotShowAllUsersWithoutAuthorizationRequest(): void
    {
        $showAllUsersRequest = $this->createRequest('users', 'GET');
        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $showAllUsersRequest->statusCode);
        $this->assertSame('Unauthorized', $showAllUsersRequest->content->message);
    }
}