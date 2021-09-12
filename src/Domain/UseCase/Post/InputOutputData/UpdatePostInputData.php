<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData;

use Rodrifarias\Blog\Infra\Exception\InputDataValidatorException;
use Valitron\Validator;

class UpdatePostInputData
{
    private function __construct(private string $title, private string $content)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public static function create(mixed $data): UpdatePostInputData
    {
        $validator = new Validator($data);
        $validator->rule('required', ['title', 'content'])->message('{field} is required');
        $validator->labels(['title' => 'Title', 'content' => 'Content']);

        if (!$validator->validate()) {
            throw new InputDataValidatorException($validator);
        }

        return new UpdatePostInputData($data['title'], $data['content']);
    }
}
