<?php

namespace Tests\Unit\InputData;

use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Domain\UseCase\Auth\InputOutputData\AuthUserCredentialsInputData;
use Rodrifarias\Blog\Infra\Exception\InputDataValidatorException;

class AuthUserCredentialsInputDataTest extends TestCase
{
    public function testShouldCreateAuthUserCredentialsInputDataInstance(): void
    {
        $credentials = AuthUserCredentialsInputData::create(['email' => 'email@test.com', 'password' => '123']);
        $this->assertInstanceOf(AuthUserCredentialsInputData::class, $credentials);
    }

    public function testShouldNotCreateAuthUserCredentialsInputDataInstanceWhenInvalidData(): void
    {
        $this->expectException(InputDataValidatorException::class);
        $this->expectExceptionMessage('Email address is required, Invalid Email address, Password is required');
        AuthUserCredentialsInputData::create(['email' => '', 'password' => '']);
    }

    public function testShouldNotCreateAuthUserCredentialsInputDataInstanceWithEmptyData(): void
    {
        $this->expectException(InputDataValidatorException::class);
        $this->expectExceptionMessage('Email address is required, Invalid Email address, Password is required');
        AuthUserCredentialsInputData::create([]);
    }
}
