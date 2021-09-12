<?php

namespace Rodrifarias\Blog\Application\Controller;

use DateTime;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Rodrifarias\Blog\Domain\UseCase\Post\Facade\PostActionFacade;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\CreatePostInputData;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\UpdatePostInputData;
use Rodrifarias\Blog\Infra\Application\Controller\ActionsControllerTemplate;
use Rodrifarias\Blog\Infra\Application\Controller\BaseController;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Delete;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Get;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Post;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Put;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\PublicAccess;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\Route;

#[Route('/posts')]
class PostController extends BaseController implements ActionsControllerTemplate
{
    private PostActionFacade $postAction;

    protected function init(): void
    {
        $this->postAction = $this->container->get(PostActionFacade::class);
    }

    #[Get('/{id:[\w-]+}'), PublicAccess(true)]
    public function show(string $id): ResponseInterface
    {
        return $this->jsonResponse($this->postAction->show($id));
    }

    #[Get, PublicAccess(true)]
    public function showAll(): ResponseInterface
    {
        return $this->jsonResponse($this->postAction->showAll()->getPosts());
    }

    #[Post]
    public function create(): ResponseInterface
    {
        $data = array_merge($this->getParsedBody(), ['createdAt' => new DateTime()]);
        $createPostInputData = CreatePostInputData::create($data);

        return $this->jsonResponse(
            $this->postAction->create($this->idUser(), $createPostInputData),
            StatusCodeInterface::STATUS_CREATED
        );
    }

    #[Put('/{id:[\w-]+}')]
    public function update(string $id): ResponseInterface
    {
        $createPostInputData = UpdatePostInputData::create($this->getParsedBody());
        $this->postAction->update($id, $this->idUser(), $createPostInputData);
        return $this->jsonResponse(statusCode: StatusCodeInterface::STATUS_NO_CONTENT, noBody: true);
    }

    #[Delete('/{id:[\w-]+}')]
    public function delete(string $id): ResponseInterface
    {
        $this->postAction->delete($id, $this->idUser());
        return $this->jsonResponse(statusCode: StatusCodeInterface::STATUS_NO_CONTENT, noBody: true);
    }
}
