<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post\Exception;

use DomainException;
use Fig\Http\Message\StatusCodeInterface;

class PostNotFoundException extends DomainException
{
    protected $message = 'Post not found';
    protected $code = StatusCodeInterface::STATUS_NOT_FOUND;
}
