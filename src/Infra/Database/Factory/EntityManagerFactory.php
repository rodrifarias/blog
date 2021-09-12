<?php

namespace Rodrifarias\Blog\Infra\Database\Factory;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;

final class EntityManagerFactory
{
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @param array<string, mixed> $connectionConfig
     * @param string[] $pathsEntities
     * @param bool $isDevMode
     * @return EntityManagerInterface
     * @throws ORMException
     */
    public static function create(array $connectionConfig, array $pathsEntities, bool $isDevMode = false): EntityManagerInterface
    {
        $config = Setup::createAnnotationMetadataConfiguration($pathsEntities, $isDevMode);
        $config->setMetadataDriverImpl(new AttributeDriver($pathsEntities));
        return EntityManager::create($connectionConfig, $config);
    }
}
