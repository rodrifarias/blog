<?php

namespace Rodrifarias\Blog\Infra\Database\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('post')]
class Post
{
    #[Id, Column(type: Types::STRING)]
    private string $id;

    #[Column(type: Types::STRING)]
    private string $title;

    #[Column(type: Types::STRING, length: 1000)]
    private string $content;

    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private DateTime $createdAt;

    #[ManyToOne(User::class)]
    private User $user;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Post
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Post
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): Post
    {
        $this->content = $content;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Post
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Post
    {
        $this->user = $user;
        return $this;
    }
}
