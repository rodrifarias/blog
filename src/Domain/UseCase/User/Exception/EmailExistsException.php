<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\Exception;

use DomainException;
use Fig\Http\Message\StatusCodeInterface;

class EmailExistsException extends DomainException
{
    protected $message = 'Email already exists';
    protected $code = StatusCodeInterface::STATUS_BAD_REQUEST;
}
