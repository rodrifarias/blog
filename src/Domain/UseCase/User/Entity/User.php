<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\Entity;

use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;

class User
{
    private string $name;
    private string $email;
    private string $password;

    public function __construct(private string $id, CreateUserInputData $createUserInputData)
    {
        $this->name = $createUserInputData->getName();
        $this->email = $createUserInputData->getEmail();
        $this->password = password_hash($createUserInputData->getPassword(), PASSWORD_DEFAULT);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }
}
