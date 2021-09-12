<?php

namespace Rodrifarias\Blog\Domain\UseCase\Auth\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface;

class InvalidUsernamePasswordException extends Exception
{
    protected $message = 'Invalid username or password';
    protected $code = StatusCodeInterface::STATUS_UNAUTHORIZED;
}
