<?php

namespace Rodrifarias\Blog\Infra\Exception;

use Fig\Http\Message\StatusCodeInterface;
use InvalidArgumentException;
use Valitron\Validator;

class InputDataValidatorException extends InvalidArgumentException
{
    public function __construct(Validator $validator)
    {
        parent::__construct($this->getMessages($validator), StatusCodeInterface::STATUS_BAD_REQUEST);
    }

    private function getMessages(Validator $validator): string
    {
        $errors = array_map(fn ($e) => implode(', ', $e), $validator->errors());
        $messages = array_unique($errors);
        return implode(', ', $messages);
    }
}
