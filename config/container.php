<?php

use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Rodrifarias\Blog\Application\Service\Jwt\AbstractJWTService;
use Rodrifarias\Blog\Application\Service\Jwt\UserJWTService;
use Rodrifarias\Blog\Application\Service\LogApp\CreateLogAppInterface;
use Rodrifarias\Blog\Application\Service\LogApp\CreateLogAppService;
use Rodrifarias\Blog\Application\Service\LogApp\Repository\LogAppRepositoryDatabase;
use Rodrifarias\Blog\Application\Service\LogApp\Repository\LogAppRepositoryInterface;
use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryDatabase;
use Rodrifarias\Blog\Domain\UseCase\Post\Repository\PostRepositoryInterface;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryDatabase;
use Rodrifarias\Blog\Domain\UseCase\User\Repository\UserRepositoryInterface;
use Rodrifarias\Blog\Infra\Application\Template\TemplateInterface;
use Rodrifarias\Blog\Infra\Application\Template\TwigTemplate;
use Rodrifarias\Blog\Infra\Database\Factory\EntityManagerFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function DI\get;

$container = new ContainerBuilder();

$entityManagerConfig = [
    'driver' => getenv('DB_DRIVER'),
    'host' => getenv('DB_HOST') . ':' . getenv('DB_PORT'),
    'dbname' => getenv('DB_NAME'),
    'user' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
];

$pathEntities = [__DIR__ . '/../src/Infra/Database/Entity'];

$templateInterfaceClosure = function () {
    $twigTemplateConfig = require 'template.php';
    $loader = new FilesystemLoader($twigTemplateConfig['filesystem-loader']);
    $options = [];

    if ($twigTemplateConfig['cache']) {
        $options['cache'] = $twigTemplateConfig['path-cache'];
    }

    $twig = new Environment($loader, $options);
    return new TwigTemplate($twig);
};

$container->addDefinitions([
    EntityManagerInterface::class => fn () => EntityManagerFactory::create($entityManagerConfig, $pathEntities, true),
    UserRepositoryInterface::class => get(UserRepositoryDatabase::class),
    PostRepositoryInterface::class => get(PostRepositoryDatabase::class),
    AbstractJWTService::class => fn () => new UserJWTService(getenv('APP_SECRET_KEY'), 'HS256'),
    CreateLogAppInterface::class => get(CreateLogAppService::class),
    LogAppRepositoryInterface::class => get(LogAppRepositoryDatabase::class),
    TemplateInterface::class => $templateInterfaceClosure,
]);

return $container->build();
