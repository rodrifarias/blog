<?php

namespace Rodrifarias\Blog\Application\Service\LogApp\Repository;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Rodrifarias\Blog\Infra\Database\Entity\LogApp;

class LogAppRepositoryDatabase implements LogAppRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(string $method, string $url, DateTime $createAt, ?string $jsonBody, ?string $userId): void
    {
        $logApp = new LogApp();
        $logApp->setUrl($url);
        $logApp->setMethod($method);
        $logApp->setCreatedAt($createAt);
        $logApp->setUserId($userId);
        $logApp->setBody($jsonBody);

        $this->entityManager->persist($logApp);
        $this->entityManager->flush();
    }
}
