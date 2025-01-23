<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Valitron\Validator;

class AuthenticateDTO
{
    private string $username;
    private string $password;

    public function __construct(array $requestData)
    {
        $validator = new Validator($requestData);
        $validator->rule('required', ['username', 'password']);
        $validator->rule('alphaNum', 'username');

        $validator
            ->rule('min', 'password', 8)
            ->rule('max', 'password', 32)
            ->rule('regex', 'password', '/[A-Z]/')  // Must contain at least one uppercase letter
            ->rule('regex', 'password', '/[a-z]/')  // Must contain at least one lowercase letter
            ->rule('regex', 'password', '/\d/')     // Must contain at least one number
            ->rule('regex', 'password', '/[@$!%*?&]/'); // Must contain at least one special character

        $this->username = $requestData['username'];
        $this->password = $requestData['password'];
    }
    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
