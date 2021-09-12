<?php

namespace Rodrifarias\Blog\Domain\Exception;

use Fig\Http\Message\StatusCodeInterface;
use InvalidArgumentException;

class InvalidNameException extends InvalidArgumentException
{
    protected $message = 'Invalid name';
    protected $code = StatusCodeInterface::STATUS_BAD_REQUEST;
}
