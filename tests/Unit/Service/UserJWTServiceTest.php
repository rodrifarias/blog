<?php

namespace Tests\Unit\Service;

use DateInterval;
use DateTime;
use Firebase\JWT\ExpiredException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Rodrifarias\Blog\Application\Service\Jwt\AbstractJWTService;
use Rodrifarias\Blog\Application\Service\Jwt\DTO\UserTokenDTO;
use Rodrifarias\Blog\Application\Service\Jwt\UserJWTService;

class UserJWTServiceTest extends TestCase
{
    private AbstractJWTService $userJWTService;
    private string $algo = 'HS256';

    protected function setUp(): void
    {
        $this->userJWTService = new UserJWTService(Uuid::uuid4(), $this->algo);
    }

    public function testShouldCreateJwtWhenExpirationDateBiggerThanNow(): void
    {
        $this->expectNotToPerformAssertions();
        $idUser = 'asd-asd';
        $name = 'Rodrigo Farias';
        $now = new DateTime();
        $expiresIn = $now->add(new DateInterval('P1Y'));

        $this->userJWTService->createToken(['idUser' => $idUser, 'name' => $name], $expiresIn);
    }

    public function testShouldDecodeJwt(): void
    {
        $idUser = 'asd-asd';
        $name = 'Rodrigo Farias';
        $now = new DateTime();
        $expiresIn = $now->add(new DateInterval('P1Y'));

        $jwt = $this->userJWTService->createToken(['idUser' => $idUser, 'name' => $name], $expiresIn);
        $decodeJwt = $this->userJWTService->decodeToken($jwt);

        $this->assertInstanceOf(UserTokenDTO::class, $decodeJwt);
        $this->assertSame($idUser, $decodeJwt->getIdUser());
        $this->assertSame($name, $decodeJwt->getName());
        $this->assertSame($expiresIn->getTimestamp(), $decodeJwt->getExpiresIn()->getTimestamp());
    }

    public function testShouldGenerateExpiredExceptionWhenExpirationDateLessThanNow(): void
    {
        $this->expectException(ExpiredException::class);
        $idUser = 'asd-asd';
        $name = 'Rodrigo Farias';
        $now = new DateTime();
        $expiresIn = $now->sub(new DateInterval('P1D'));

        $jwt = $this->userJWTService->createToken(['idUser' => $idUser, 'name' => $name], $expiresIn);
        $this->userJWTService->decodeToken($jwt);
    }
}
