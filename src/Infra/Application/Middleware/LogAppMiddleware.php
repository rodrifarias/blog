<?php

namespace Rodrifarias\Blog\Infra\Application\Middleware;

use DateTime;
use Firebase\JWT\ExpiredException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Rodrifarias\Blog\Application\Service\Jwt\AbstractJWTService;
use Rodrifarias\Blog\Application\Service\LogApp\CreateLogAppInterface;
use Slim\Psr7\UploadedFile;

final class LogAppMiddleware implements MiddlewareInterface
{
    public function __construct(
        private CreateLogAppInterface $createLogApp,
        private AbstractJWTService $userJWTService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->createLogApp->execute(
            $request->getMethod(),
            $request->getUri(),
            new DateTime(),
            $this->getBodyRequest($request),
            $this->getIdUserToken($request),
        );

        return $handler->handle($request);
    }

    private function getBodyRequest(ServerRequestInterface $request): array
    {
        $parsedBody = $request->getParsedBody();
        $bodyContent = $request->getBody()->getContents();
        $jsonParams = $parsedBody ?? (array)json_decode($bodyContent);

        $files = array_map(
            fn (UploadedFile $file) => ['name' => $file->getClientFilename(), 'type' => $file->getClientMediaType()],
            $request->getUploadedFiles()
        );

        return array_merge($jsonParams, $files);
    }

    private function getIdUserToken(ServerRequestInterface $request): ?string
    {
        $authorization = $request->getHeader('Authorization');

        if (!$authorization) {
            return null;
        }

        try {
            $decodedToken = $this->userJWTService->decodeToken($authorization[0]);
            return $decodedToken->getIdUser();
        } catch (ExpiredException) {
            return null;
        }
    }
}
