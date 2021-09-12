<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post;

use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\PostOutputData;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\PostOutputDataCollection;

class ShowAllPost extends AbstractUseCasePost
{
    public function execute(): PostOutputDataCollection
    {
        $postsCollection = new PostOutputDataCollection();
        $posts = $this->postRepository->findAll();

        foreach ($posts as $post) {
            $postsCollection->add(new PostOutputData(
                $post->getId(),
                $post->getUser()->getId(),
                $post->getUser()->getName(),
                $post->getTitle(),
                $post->getContent(),
                $post->getCreatedAt()->format('Y-m-d H:i:s'),
            ));
        }

        return $postsCollection;
    }
}
