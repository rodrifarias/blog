<?php

namespace Tests\Integration\Api;

use DateInterval;
use Dotenv\Dotenv;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Rodrifarias\Blog\Application\Service\Jwt\UserJWTService;
use stdClass;

class TestCaseRequest extends TestCase
{
    private string $urlBase = 'http://localhost:8000/';
    protected string $userNameDefault = 'User Test';
    protected string $emailDefault = 'test-user-test@test.com';
    protected string $passwordDefault = '123';

    private static string $token = '';
    private static string $idUser = '';
    private static string $idUserNew = '';
    private static string $tokenNew = '';

    public function createUserDefault(): stdClass
    {
        $request = $this->createRequest('users', 'POST', [
            'name' => $this->userNameDefault,
            'email' => $this->emailDefault,
            'password' => $this->passwordDefault
        ]);

        $requestAuth = $this->createRequest('auth', 'POST', [
            'email' => $this->emailDefault,
            'password' => $this->passwordDefault
        ]);

        self::$idUser = $request->content->id;
        self::$token = 'Bearer ' . $requestAuth->content->token;
        return $request;
    }

    public function deleteUserDefault(?string $id = null): void
    {
        $idUser = $id ?: self::$idUser;
        $d = $this->createRequestWithAuthorization('users/' . $idUser, 'DELETE');
    }

    public function createRequestWithAuthorization(string $path, string $method, array $jsonParams = [], array $headers = []): stdClass
    {
        $headers = array_merge($headers, ['Authorization' => self::$token]);
        return $this->createRequest($path, $method, $jsonParams, $headers);
    }

    public function createRequest(string $path, string $method, array $jsonParams = [], array $headers = []): stdClass
    {
        $url = $this->urlBase . $path;

        $paramsRequest = [
            'json' => $jsonParams,
            'http_errors' => false,
            'headers' => $headers,
        ];

        $client = new Client();
        $request = $client->request($method, $url, $paramsRequest);

        $stdClass = new stdClass();
        $stdClass->statusCode = $request->getStatusCode();
        $stdClass->content = json_decode($request->getBody()->getContents());
        $stdClass->header = (array)$request->getHeaders();

        return $stdClass;
    }

    public function createTokenUnknownUser(): string
    {
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__. '/../../../');
        $dotenv->load();
        $jwt = getenv('APP_SECRET_KEY');
        $userJwtService = new UserJWTService($jwt, 'HS256');
        $now = new \DateTime();

        return $userJwtService->createToken(
            ['idUser' => 'abc', 'name' => 'abc'],
            $now->add(new DateInterval('P1D'))
        );
    }

    public function createTokenNewUser(): string
    {
        $user = $this->createRequest('users', 'POST', [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'password' => '123'
        ]);

        $authorization = $this->createRequest('auth', 'POST', [
            'email' => 'test@user.com',
            'password' => '123'
        ]);

        self::$idUserNew = $user->content->id;
        self::$tokenNew = $authorization->content->token;

        return $authorization->content->token;
    }

    public function deleteNewUser(): void
    {
        $this->createRequest(path: 'users/' . self::$idUserNew, method: 'DELETE', headers: [
            'Authorization' => 'Bearer ' . self::$tokenNew
        ]);
    }
}
