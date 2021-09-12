<?php

namespace Rodrifarias\Blog\Infra\Application\Controller;

use Psr\Http\Message\ResponseInterface;

interface ActionsControllerTemplate
{
    public function show(string $id): ResponseInterface;
    public function showAll(): ResponseInterface;
    public function create(): ResponseInterface;
    public function update(string $id): ResponseInterface;
    public function delete(string $id): ResponseInterface;
}
