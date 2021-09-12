<?php

namespace Tests\Integration\Api\Auth;

use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class AuthenticateUserRequest extends TestCaseRequest
{
    protected function setUp(): void
    {
        $this->createUserDefault();
    }

    protected function tearDown(): void
    {
        $this->deleteUserDefault();
    }

    public function testShouldAuthenticateUserRequest(): void
    {
        $authRequest = $this->createRequest('auth', 'POST', [
            'email' => $this->emailDefault,
            'password' => $this->passwordDefault
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_OK, $authRequest->statusCode);
        $this->assertObjectHasAttribute('token', $authRequest->content);
        $this->assertObjectHasAttribute('name', $authRequest->content);
        $this->assertObjectHasAttribute('email', $authRequest->content);
        $this->assertSame($this->userNameDefault, $authRequest->content->name);
        $this->assertSame($this->emailDefault, $authRequest->content->email);
        $this->assertIsString($authRequest->content->token);
    }

    public function testShouldNotAuthenticateUserWhenIncorrectEmailRequest(): void
    {
        $authRequest = $this->createRequest('auth', 'POST', [
            'email' => 'teste@teste.com.br',
            'password' => $this->passwordDefault
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_UNAUTHORIZED, $authRequest->statusCode);
        $this->assertSame('Invalid username or password', $authRequest->content->description);
    }

    public function testShouldNotAuthenticateUserWhenIncorrectPasswordRequest(): void
    {
        $authRequest = $this->createRequest('auth', 'POST', [
            'email' => $this->emailDefault,
            'password' => 'abc'
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_UNAUTHORIZED, $authRequest->statusCode);
        $this->assertSame('Invalid username or password', $authRequest->content->description);
    }

    public function testShouldNotAuthenticateUserWhenInvalidEmailRequest(): void
    {
        $authRequest = $this->createRequest('auth', 'POST', [
            'email' => 'teste@teste',
            'password' => 'abc'
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_BAD_REQUEST, $authRequest->statusCode);
        $this->assertSame('Invalid Email address', $authRequest->content->description);
    }

    public function testShouldNotAuthenticateUserWhenPasswordIsEmptyRequest(): void
    {
        $authRequest = $this->createRequest('auth', 'POST', [
            'email' => 'teste@teste.com',
            'password' => null
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_BAD_REQUEST, $authRequest->statusCode);
        $this->assertSame('Password is required', $authRequest->content->description);
    }
}