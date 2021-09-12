<?php

namespace Tests\Integration\Api\User;

use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class CreateUserRequestTest extends TestCaseRequest
{
    protected function tearDown(): void
    {
        $this->deleteUserDefault();
    }

    public function testShouldCreateUserRequest(): void
    {
        $request = $this->createUserDefault();

        $this->assertObjectHasAttribute('id', $request->content);
        $this->assertObjectHasAttribute('name', $request->content);
        $this->assertObjectHasAttribute('email', $request->content);
        $this->assertSame('User Test', $request->content->name);
        $this->assertSame('test-user-test@test.com', $request->content->email);
        $this->assertIsString($request->content->id);
        $this->assertSame( StatusCodeInterface::STATUS_CREATED, $request->statusCode);
    }

    public function testShouldNotCreateUserWhenNotHaveNameRequest(): void
    {
        $createUserRequest = $this->createRequest('users', 'POST', [
            'name' => null,
            'email' => 'email@teste',
            'password' => '123'
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR, $createUserRequest->statusCode);
        $this->assertSame( 'Name is required', $createUserRequest->content->description);
    }

    public function testShouldNotCreateUserWhenNotHaveEmailRequest(): void
    {
        $createUserRequest = $this->createRequest('users', 'POST', [
            'name' => 'Rodrigo Farias',
            'email' => null,
            'password' => '123'
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR, $createUserRequest->statusCode);
        $this->assertSame( 'Email is required', $createUserRequest->content->description);
    }

    public function testShouldNotCreateUserWhenNotHavePasswordRequest(): void
    {
        $createUserRequest = $this->createRequest('users', 'POST', [
            'name' => 'Rodrigo Farias',
            'email' => 'email@teste',
            'password' => null
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR, $createUserRequest->statusCode);
        $this->assertSame( 'Password is required', $createUserRequest->content->description);
    }

    public function testShouldNotCreateUserWhenInvalidNameRequest(): void
    {
        $createUserRequest = $this->createRequest('users', 'POST', [
            'name' => 'Rodrigo',
            'email' => 'email@teste',
            'password' => '123'
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_BAD_REQUEST, $createUserRequest->statusCode);
        $this->assertSame( 'Invalid name', $createUserRequest->content->description);
    }

    public function testShouldNotCreateUserWhenInvalidEmailRequest(): void
    {
        $createUserRequest = $this->createRequest('users', 'POST', [
            'name' => 'Rodrigo Farias',
            'email' => 'email@teste',
            'password' => '123'
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_BAD_REQUEST, $createUserRequest->statusCode);
        $this->assertSame( 'Invalid email', $createUserRequest->content->description);
    }

    public function testShouldNotCreateUserWhenExistsEmailRequest(): void
    {
        $this->createUserDefault();

        $createUserRequest = $this->createRequest('users', 'POST', [
            'name' => 'Rodrigo Farias',
            'email' => $this->emailDefault,
            'password' => '123'
        ]);

        $this->assertSame( StatusCodeInterface::STATUS_BAD_REQUEST, $createUserRequest->statusCode);
        $this->assertSame( 'User already exists', $createUserRequest->content->description);
    }

}