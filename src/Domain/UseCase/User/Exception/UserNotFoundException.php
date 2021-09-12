<?php

namespace Rodrifarias\Blog\Domain\UseCase\User\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface;

class UserNotFoundException extends Exception
{
    protected $message = 'User not found';
    protected $code = StatusCodeInterface::STATUS_NOT_FOUND;
}
