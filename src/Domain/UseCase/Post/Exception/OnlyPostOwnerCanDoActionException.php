<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post\Exception;

use DomainException;
use Fig\Http\Message\StatusCodeInterface;

class OnlyPostOwnerCanDoActionException extends DomainException
{
    protected $message = 'Only the post owner can do action';
    protected $code = StatusCodeInterface::STATUS_BAD_REQUEST;
}
