<?php

namespace Rodrifarias\Blog\Domain\ValueObject;

use Rodrifarias\Blog\Domain\Exception\InvalidNameException;

class Name
{
    private string $value;

    public function __construct(mixed $value)
    {
        $isValidName = preg_match('/^([A-Za-z]+ )+([A-Za-z])+$/', $value);

        if (!$isValidName) {
            throw new InvalidNameException();
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
