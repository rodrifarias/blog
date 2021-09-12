<?php

namespace Rodrifarias\Blog\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Rodrifarias\Blog\Infra\Application\Controller\BaseController;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Get;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\PublicAccess;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\Route;

#[Route('/swagger')]
class SwaggerController extends BaseController
{
    #[Get('/ui'), PublicAccess(true)]
    public function index(): ResponseInterface
    {
        return $this->htmlResponse('/swagger.twig');
    }

    #[Get('/config'), PublicAccess(true)]
    public function swaggerConfig(): ResponseInterface
    {
        $swaggerFile = file_get_contents(__DIR__ . '/../../../swagger.json');
        return $this->jsonResponse(json_decode($swaggerFile));
    }
}