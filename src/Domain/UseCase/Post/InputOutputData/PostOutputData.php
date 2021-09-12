<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData;

use JsonSerializable;

class PostOutputData implements JsonSerializable
{
    public function __construct(
        private string $idPost,
        private string $idUser,
        private string $userName,
        private string $title,
        private string $content,
        private string $createdAt,
    ) {
    }

    public function getIdPost(): string
    {
        return $this->idPost;
    }

    public function getIdUser(): string
    {
        return $this->idUser;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
