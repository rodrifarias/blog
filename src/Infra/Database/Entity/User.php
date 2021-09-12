<?php

namespace Rodrifarias\Blog\Infra\Database\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('user')]
class User
{
    #[Id, Column(type: Types::STRING)]
    private string $id;

    #[Column(type: Types::STRING)]
    private string $name;

    #[Column(type: Types::STRING, unique: true)]
    private string $email;

    #[Column(type: Types::STRING)]
    private string $password;

    #[OneToMany('user', Post::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function addPost(Post $post): self
    {
        $this->posts->add($post);
        $post->setUser($this);
        return $this;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }
}
