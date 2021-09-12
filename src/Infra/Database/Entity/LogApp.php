<?php

namespace Rodrifarias\Blog\Infra\Database\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('log_app')]
class LogApp
{
    #[Id, GeneratedValue, Column(type: Types::INTEGER)]
    private string $id;

    #[Column(type: Types::STRING)]
    private string $method;

    #[Column(type: Types::STRING)]
    private string $url;

    #[Column(type: Types::STRING, nullable: true)]
    private ?string $body;

    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private DateTime $createdAt;

    #[Column(name: 'user_id', type: Types::STRING, nullable: true)]
    private ?string $userId;

    public function getId(): string
    {
        return $this->id;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): LogApp
    {
        $this->method = $method;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): LogApp
    {
        $this->url = $url;
        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): LogApp
    {
        $this->body = $body;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): LogApp
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): LogApp
    {
        $this->userId = $userId;
        return $this;
    }
}
