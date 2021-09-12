<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\InputOutputData;

use Rodrifarias\Blog\Domain\ValueObject\Email;
use Rodrifarias\Blog\Domain\ValueObject\Name;
use InvalidArgumentException;

class CreateUserInputData
{
    private function __construct(
        private Name $name,
        private Email $email,
        private string $password,
    ) {
    }

    public function getName(): string
    {
        return $this->name->getValue();
    }

    public function getEmail(): string
    {
        return $this->email->getValue();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public static function create(mixed $data): CreateUserInputData
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Name, email and password are required');
        }

        if (!array_key_exists('name', $data) || !is_string($data['name'])) {
            throw new InvalidArgumentException('Name is required');
        }

        if (!array_key_exists('email', $data) || !is_string($data['email'])) {
            throw new InvalidArgumentException('Email is required');
        }

        if (!array_key_exists('password', $data) || !is_string($data['password'])) {
            throw new InvalidArgumentException('Password is required');
        }

        return new CreateUserInputData(new Name($data['name']), new Email($data['email']), $data['password']);
    }
}
