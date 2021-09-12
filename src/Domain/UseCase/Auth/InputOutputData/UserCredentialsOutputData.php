<?php

namespace Rodrifarias\Blog\Domain\UseCase\Auth\InputOutputData;

use JsonSerializable;

class UserCredentialsOutputData implements JsonSerializable
{
    public function __construct(private string $email, private string $name, private string $token)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
