<?php

namespace Rodrifarias\Blog\Domain\Exception;

use Fig\Http\Message\StatusCodeInterface;
use InvalidArgumentException;

class InvalidEmailException extends InvalidArgumentException
{
    protected $message = 'Invalid email';
    protected $code = StatusCodeInterface::STATUS_BAD_REQUEST;
}
