<?php

namespace Tests\Unit\ValueObject;

use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Domain\Exception\InvalidNameException;
use Rodrifarias\Blog\Domain\ValueObject\Name;

class NameTest extends TestCase
{
    public function testShouldCreateNameInstance(): void
    {
        $name = new Name('Rodrigo Farias');
        $this->assertSame('Rodrigo Farias', $name->getValue());
    }

    public function testShouldGenerateInvalidNameException(): void
    {
        $this->expectException(InvalidNameException::class);
        new Name('Rodrigo Farias 123');
    }
}
