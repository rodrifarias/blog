<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\InputOutputData;

use Rodrifarias\Blog\Domain\ValueObject\Email;
use Rodrifarias\Blog\Domain\ValueObject\Name;
use InvalidArgumentException;

class UpdateUserInputData
{
    private function __construct(
        private Name $name,
        private Email $email,
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

    public static function create(mixed $data): UpdateUserInputData
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Name and email are required');
        }

        if (!array_key_exists('name', $data) || !is_string($data['name'])) {
            throw new InvalidArgumentException('Name is required');
        }

        if (!array_key_exists('email', $data) || !is_string($data['email'])) {
            throw new InvalidArgumentException('Email is required');
        }

        return new UpdateUserInputData(
            new Name($data['name']),
            new Email($data['email']),
        );
    }
}
