<?php

namespace Tests\Unit\ValueObject;

use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Domain\Exception\InvalidEmailException;
use Rodrifarias\Blog\Domain\ValueObject\Email;

class EmailTest extends TestCase
{
    public function testShouldCreateNameInstance(): void
    {
        $email = new Email('rodrigo@teste.com');
        $this->assertSame('rodrigo@teste.com', $email->getValue());
    }

    public function testShouldGenerateInvalidNameException(): void
    {
        $this->expectException(InvalidEmailException::class);
        new Email('Rodrigo Farias 123');
    }
}
