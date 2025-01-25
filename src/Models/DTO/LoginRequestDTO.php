<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Valitron\Validator;

class LoginRequestDTO
{
    private string $username;
    private string $password;

    public function __construct(array $requestData)
    {
        $validator = new Validator($requestData);
        $validator->rule('required', ['username', 'password']);
        $validator->rule('alphaNum', 'username');

        $validator
            ->rule('lengthBetween', 'password', 8, 32)->message('Password must be between 8 and 32 characters long')
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
