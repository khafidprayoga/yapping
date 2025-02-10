<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Valitron\Validator;

class UserDTO
{
    public string $fullName;
    public string $username;
    public string $password;

    /**
     * @throws HttpException
     */
    public function __construct(array $requestData)
    {

        $validator = new Validator($requestData);
        $validator->rule('required', ['fullName', 'username', 'password', 'passwordVerify']);
        $validator->rule('email', 'username')->message('Username must be a valid email address');

        $validator->rule('regex', 'password', '/[A-Z]/')->message('uppercase letter');
        $validator->rule('regex', 'password', '/[a-z]/')->message('lowercase letter');
        $validator->rule('regex', 'password', '/\d/')->message('one numeric');
        $validator->rule('regex', 'password', '/[@$!%*?&]/')->message("special character");
        $validator->rule('lengthBetween', 'password', 8, 32)->message('and between 8 and 32 characters long');

        $validator->rule('equals', 'passwordVerify', 'password')->message("Passwords don't match");

        $isValid = $validator->validate();

        if (!$isValid) {
            $errors = $validator->errors();
            throw new HttpException($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->fullName = $requestData['fullName'];
        $this->username = $requestData['username'];
        $this->password = $requestData['password'];
    }
}
