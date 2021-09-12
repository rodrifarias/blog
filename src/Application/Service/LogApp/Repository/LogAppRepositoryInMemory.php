<?php

namespace Rodrifarias\Blog\Application\Service\LogApp\Repository;

use DateTime;
use Rodrifarias\Blog\Infra\Database\Entity\LogApp;

class LogAppRepositoryInMemory implements LogAppRepositoryInterface
{
    /** @var LogApp[] */
    private array $logs = [];

    public function save(string $method, string $url, DateTime $createAt, ?string $jsonBody, ?string $userId): void
    {
        $logApp = new LogApp();
        $logApp->setUrl($url);
        $logApp->setMethod($method);
        $logApp->setCreatedAt($createAt);
        $logApp->setUserId($userId);
        $logApp->setBody($jsonBody);

        $this->logs[] = $logApp;
    }

    /**
     * @return LogApp[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }
}
