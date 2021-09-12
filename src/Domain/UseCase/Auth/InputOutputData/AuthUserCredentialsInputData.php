<?php

namespace Rodrifarias\Blog\Domain\UseCase\Auth\InputOutputData;

use Rodrifarias\Blog\Domain\ValueObject\Email;
use Rodrifarias\Blog\Infra\Exception\InputDataValidatorException;
use Valitron\Validator;

class AuthUserCredentialsInputData
{
    private function __construct(private Email $email, private string $password)
    {
    }

    public function getEmail(): string
    {
        return $this->email->getValue();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public static function create(mixed $data): AuthUserCredentialsInputData
    {
        $validator = new Validator($data);
        $validator->rule('required', ['email', 'password'])->message('{field} is required');
        $validator->rule('email', ['email'])->message('Invalid {field}');
        $validator->labels(['email' => 'Email address', 'password' => 'Password']);

        if (!$validator->validate()) {
            throw new InputDataValidatorException($validator);
        }

        return new AuthUserCredentialsInputData(new Email($data['email']), $data['password']);
    }
}
