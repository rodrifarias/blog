<?php

namespace Rodrifarias\Blog\Domain\UseCase\Post\InputOutputData;

use DateTime;
use Rodrifarias\Blog\Infra\Exception\InputDataValidatorException;
use Valitron\Validator;

class CreatePostInputData
{
    private function __construct(private string $title, private string $content, private DateTime $createdAt)
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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public static function create(mixed $data): CreatePostInputData
    {
        $validator = new Validator($data);
        $validator->rule('required', ['title', 'content', 'createdAt'])->message('{field} is required');
        $validator->rule('date', ['createdAt'])->message('{field} is required');
        $validator->labels(['title' => 'Title', 'content' => 'Content', 'createdAt' => 'Created at']);

        if (!$validator->validate()) {
            throw new InputDataValidatorException($validator);
        }

        return new CreatePostInputData($data['title'], $data['content'], $data['createdAt']);
    }
}
