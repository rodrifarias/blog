<?php

namespace Rodrifarias\Blog\Application\Service\Jwt;

use DateTimeInterface;

abstract class AbstractJWTService
{
    public function __construct(protected string $secretKey, protected string $algo)
    {
    }

    abstract public function createToken(array $payload, DateTimeInterface $expiresIn): string;
    abstract public function decodeToken(string $token): object;
}
