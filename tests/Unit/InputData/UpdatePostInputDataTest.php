<?php

namespace Tests\Unit\InputData;

use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\UpdatePostInputData;
use Rodrifarias\Blog\Infra\Exception\InputDataValidatorException;

class UpdatePostInputDataTest extends TestCase
{
    public function testShouldUpdatePostInputDataInputDataInstance(): void
    {
        $updatePost = UpdatePostInputData::create(['title' => 'TITLE', 'content' => 'CONTENT']);
        $this->assertInstanceOf(UpdatePostInputData::class, $updatePost);
    }

    public function testShouldNotCreateAuthUserCredentialsInputDataInstanceWhenInvalidData(): void
    {
        $this->expectException(InputDataValidatorException::class);
        $this->expectExceptionMessage('Title is required, Content is required');
        UpdatePostInputData::create(['title' => '', 'content' => '', 'createdAt' => null]);
    }

    public function testShouldNotCreateAuthUserCredentialsInputDataInstanceWhenEmptyData(): void
    {
        $this->expectException(InputDataValidatorException::class);
        $this->expectExceptionMessage('Title is required, Content is required');
        UpdatePostInputData::create([]);
    }
}
