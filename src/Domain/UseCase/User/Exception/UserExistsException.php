<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\Exception;

use DomainException;
use Fig\Http\Message\StatusCodeInterface;

class UserExistsException extends DomainException
{
    protected $message = 'User already exists';
    protected $code = StatusCodeInterface::STATUS_BAD_REQUEST;
}
