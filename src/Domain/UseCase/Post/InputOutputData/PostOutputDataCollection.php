<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData;

class PostOutputDataCollection
{
    /** @var PostOutputData[] */
    private array $posts = [];

    public function add(PostOutputData $createPostOutputData)
    {
        $this->posts[] = $createPostOutputData;
    }

    /**
     * @return PostOutputData[]
     */
    public function getPosts(): array
    {
        return $this->posts;
    }
}
