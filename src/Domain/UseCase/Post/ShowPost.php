<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post;

use Exception;
use Rodrifarias\Blog\Domain\UseCase\Post\Exception\PostNotFoundException;
use Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData\PostOutputData;

class ShowPost extends AbstractUseCasePost
{
    /**
     * @throws Exception
     * @throws PostNotFoundException
     */
    public function execute(string $idPost): PostOutputData
    {
        $post = $this->postRepository->findById($idPost);

        return new PostOutputData(
            $post->getId(),
            $post->getUser()->getId(),
            $post->getUser()->getName(),
            $post->getTitle(),
            $post->getContent(),
            $post->getCreatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
