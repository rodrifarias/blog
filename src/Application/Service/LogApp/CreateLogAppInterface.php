<?php

namespace Rodrifarias\Blog\Application\Service\LogApp;

use DateTime;

interface CreateLogAppInterface
{
    public function execute(string $method, string $url, DateTime $createAt, ?array $body, ?string $userId): void;
}
