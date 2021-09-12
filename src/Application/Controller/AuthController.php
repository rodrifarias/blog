<?php

namespace Rodrifarias\Blog\Application\Controller;

use DateInterval;
use DateTime;
use Psr\Http\Message\ResponseInterface;
use Rodrifarias\Blog\Infra\Application\Controller\BaseController;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\Route;
use Rodrifarias\Blog\Domain\UseCase\Auth\AuthenticateUser;
use Rodrifarias\Blog\Domain\UseCase\Auth\CreateCredentialsUser;
use Rodrifarias\Blog\Domain\UseCase\Auth\InputOutputData\AuthUserCredentialsInputData;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\HttpMethods\Post;
use Rodrifarias\Blog\Infra\Application\Route\Attributes\PublicAccess;

#[Route('/auth')]
class AuthController extends BaseController
{
    private AuthenticateUser $authenticateUser;
    private CreateCredentialsUser $createCredentialsUser;

    protected function init(): void
    {
        $this->authenticateUser = $this->container->get(AuthenticateUser::class);
        $this->createCredentialsUser = $this->container->get(CreateCredentialsUser::class);
    }

    #[Post, PublicAccess(true)]
    public function auth(): ResponseInterface
    {
        $authUserCredentialsInputData = AuthUserCredentialsInputData::create($this->getParsedBody());
        $this->authenticateUser->execute($authUserCredentialsInputData);

        $now = new DateTime();

        return $this->jsonResponse($this->createCredentialsUser->execute(
            $authUserCredentialsInputData->getEmail(),
            $now->add(new DateInterval('P1D'))
        ));
    }
}
