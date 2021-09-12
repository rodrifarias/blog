<?php

namespace Rodrifarias\Blog\Application\Service\LogApp;

use DateTime;
use Rodrifarias\Blog\Application\Service\LogApp\Repository\LogAppRepositoryInterface;

class CreateLogAppService implements CreateLogAppInterface
{
    public function __construct(private LogAppRepositoryInterface $logAppRepository)
    {
    }

    public function execute(string $method, string $url, DateTime $createAt, ?array $body, ?string $userId): void
    {
        $jsonBody = $body ? json_encode($body) : null;
        $this->logAppRepository->save($method, $url, $createAt, $jsonBody, $userId);
    }
}
