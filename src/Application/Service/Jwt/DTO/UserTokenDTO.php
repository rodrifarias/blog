<?php

namespace Rodrifarias\Blog\Application\Service\Jwt\DTO;

use DateTime;

class UserTokenDTO
{
    public function __construct(private string $idUser, private string $name, private DateTime $expiresIn)
    {
    }

    public function getIdUser(): string
    {
        return $this->idUser;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getExpiresIn(): DateTime
    {
        return $this->expiresIn;
    }
}
