<?php

namespace Tests\Integration\Api\Post;

use Fig\Http\Message\StatusCodeInterface;
use Tests\Integration\Api\TestCaseRequest;

class ShowAllPostRequestTest extends TestCaseRequest
{
    public function testShouldShowAllPostRequest(): void
    {
        $requestShowAllPost = $this->createRequest('posts', 'GET');
        $content = $requestShowAllPost->content;

        $this->assertSame( StatusCodeInterface::STATUS_OK, $requestShowAllPost->statusCode);
        $this->assertIsArray($content);

        if ($content) {
            $validAttributes = ['idPost', 'idUser', 'userName', 'title', 'content', 'createdAt'];
            $post = $content[0];
            $diffAttributes = array_diff(array_keys((array)$post), $validAttributes);

            $this->assertEmpty($diffAttributes);
            $this->assertObjectHasAttribute( 'idPost', $post);
            $this->assertObjectHasAttribute( 'idUser', $post);
            $this->assertObjectHasAttribute( 'title', $post);
            $this->assertObjectHasAttribute( 'content', $post);
            $this->assertObjectHasAttribute( 'createdAt', $post);
            $this->assertObjectHasAttribute( 'userName', $post);
            $this->assertIsString($post->idPost);
            $this->assertIsString($post->idUser);
            $this->assertIsString($post->title);
            $this->assertIsString($post->content);
            $this->assertIsString($post->createdAt);
            $this->assertIsString($post->userName);
        }
    }
}