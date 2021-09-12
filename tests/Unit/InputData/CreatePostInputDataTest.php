<?php

namespace Tests\Unit\InputData;

use DateTime;
use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Infra\Exception\InputDataValidatorException;

class CreatePostInputDataTest extends TestCase
{
    public function testShouldCreatePostInputDataInstance(): void
    {
        $post = CreatePostInputData::create(['title' => 'TITLE', 'content' => 'CONTENT', 'createdAt' => new DateTime()]);
        $this->assertInstanceOf(CreatePostInputData::class, $post);
    }

    public function testShouldNotCreatePostInputDataInstanceWhenInvalidData(): void
    {
        $this->expectException(InputDataValidatorException::class);
        $this->expectExceptionMessage('Title is required, Content is required, Created at is required');
        CreatePostInputData::create(['title' => '', 'content' => '', 'createdAt' => null]);
    }

    public function testShouldNotCreatePostInputDataInstanceWhenEmptyData(): void
    {
        $this->expectException(InputDataValidatorException::class);
        $this->expectExceptionMessage('Title is required, Content is required, Created at is required');
        CreatePostInputData::create([]);
    }
}
