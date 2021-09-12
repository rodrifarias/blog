<?php

namespace Rodrifarias\Blog\Domain\ValueObject;

use Rodrifarias\Blog\Domain\Exception\InvalidEmailException;

class Email
{
    private string $value;

    public function __construct(mixed $value)
    {
        $isValidEmail = filter_var($value, FILTER_VALIDATE_EMAIL);

        if (!$isValidEmail) {
            throw new InvalidEmailException();
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
