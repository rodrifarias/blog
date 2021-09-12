<?php

namespace Rodrifarias\Blog\Infra\Application\Controller;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Json\Json;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rodrifarias\Blog\Infra\Application\Template\TemplateInterface;
use Slim\Psr7\Response;

abstract class BaseController
{
    protected ServerRequestInterface $request;
    protected TemplateInterface $template;
    protected string $idUser;

    public function __construct(protected ContainerInterface $container, protected Response $response)
    {
        $this->template = $this->container->get(TemplateInterface::class);
        $this->init();
    }

    protected function init(): void
    {
    }

    protected function jsonResponse(
        mixed $data = null,
        int $statusCode = StatusCodeInterface::STATUS_OK,
        bool $noBody = false
    ): ResponseInterface {
        $this->response = $this->response->withStatus($statusCode);
        $this->response = $this->response->withHeader('Content-Type', 'application/json');

        if (!$noBody) {
            $this->response->getBody()->write(Json::encode($data));
        }

        return $this->response;
    }

    protected function htmlResponse(string $template, array $vars = [], int $statusCode = 200): ResponseInterface
    {
        $this->response = $this->response->withStatus($statusCode);
        $this->response = $this->response->withHeader('Content-Type', 'text/html');

        $view = $this->template->render($template, $vars);
        $this->response->getBody()->write($view);

        return $this->response;
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function setUser(?string $jsonUserRequest): void
    {
        if ($jsonUserRequest) {
            $user = json_decode($jsonUserRequest);
            $this->idUser = $user->idUser;
        }
    }

    public function idUser(): string
    {
        return $this->idUser;
    }

    public function getParsedBody(): array|null|object
    {
        return $this->request->getParsedBody();
    }

    public function getUploadedFiles(): array
    {
        return $this->request->getUploadedFiles();
    }
}
