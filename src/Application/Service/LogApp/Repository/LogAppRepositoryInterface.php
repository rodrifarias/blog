<?php

namespace Rodrifarias\Blog\Application\Service\LogApp\Repository;

use DateTime;

interface LogAppRepositoryInterface
{
    public function save(string $method, string $url, DateTime $createAt, ?string $jsonBody, ?string $userId): void;
}
