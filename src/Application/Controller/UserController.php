<?php

namespace Rodrifarias\Blog\Application\Controller;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Rodrifarias\Blog\Domain\UseCase\User\Facade\UserActionFacade;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\CreateUserInputData;
use Rodrifarias\Blog\Domain\UseCase\User\InputOutputData\UpdateUserInputData;
use Rodrifarias\Blog\Infra\Application\Controller\ActionsControllerTemplate;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Delete;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Get;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Post;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Put;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\PublicAccess;
use Rodrifarias\Blog\Infra\Application\Controller\BaseController;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\Route;

#[Route('/users')]
class UserController extends BaseController implements ActionsControllerTemplate
{
    private UserActionFacade $userAction;

    protected function init(): void
    {
        $this->userAction = $this->container->get(UserActionFacade::class);
    }

    #[Get('/{id:[\w-]+}')]
    public function show(string $id): ResponseInterface
    {
        return $this->jsonResponse($this->userAction->show($id));
    }

    #[Get]
    public function showAll(): ResponseInterface
    {
        return $this->jsonResponse($this->userAction->showAll()->users());
    }

    #[Post, PublicAccess(true)]
    public function create(): ResponseInterface
    {
        $createUserInputData = CreateUserInputData::create($this->getParsedBody());
        return $this->jsonResponse(
            $this->userAction->create($createUserInputData),
            StatusCodeInterface::STATUS_CREATED
        );
    }

    #[Put('/{id:[\w-]+}')]
    public function update(string $id): ResponseInterface
    {
        $updateUserInputData = UpdateUserInputData::create($this->getParsedBody());
        $this->userAction->update($id, $updateUserInputData);
        return $this->jsonResponse(statusCode: StatusCodeInterface::STATUS_NO_CONTENT, noBody: true);
    }

    #[Delete('/{id:[\w-]+}')]
    public function delete(string $id): ResponseInterface
    {
        $this->userAction->delete($id);
        return $this->jsonResponse(statusCode: StatusCodeInterface::STATUS_NO_CONTENT, noBody: true);
    }
}
