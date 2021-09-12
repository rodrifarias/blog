<?php

namespace Rodrifarias\Blog\Application\Service\Jwt;

use DateTime;
use DateTimeInterface;
use Firebase\JWT\JWT;
use Rodrifarias\Blog\Application\Service\Jwt\DTO\UserTokenDTO;

class UserJWTService extends AbstractJWTService
{
    public function createToken(array $payload, DateTimeInterface $expiresIn): string
    {
        $payload = array_merge($payload, ['exp' => $expiresIn->getTimestamp()]);
        return JWT::encode($payload, $this->secretKey, $this->algo);
    }

    public function decodeToken(string $token): UserTokenDTO
    {
        $bearerToken = str_replace('Bearer ', '', $token);
        $decodedToken = JWT::decode($bearerToken, $this->secretKey, [$this->algo]);
        $expiresIn = new DateTime();
        $expiresIn->setTimestamp($decodedToken->exp);

        return new UserTokenDTO($decodedToken->idUser, $decodedToken->name, $expiresIn);
    }
}
