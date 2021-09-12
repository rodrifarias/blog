<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\InputOutputData;

class UserOutputDataCollection
{
    /**
     * @var UserOutputData[]
     */
    private array $users = [];

    public function add(UserOutputData $user): void
    {
        $this->users[] = $user;
    }

    /**
     * @return UserOutputData[]
     */
    public function users(): array
    {
        return $this->users;
    }
}
