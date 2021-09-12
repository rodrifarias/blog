<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\InputOutputData;

use JsonSerializable;

class UserOutputData implements JsonSerializable
{
    public function __construct(private string $id, private string $name, private string $email)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
