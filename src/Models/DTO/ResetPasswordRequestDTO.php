<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Valitron\Validator;

class ResetPasswordRequestDTO
{
    public string $username;
    public string $password;

    /**
     * @throws HttpException
     */
    public function __construct(array $requestData)
    {

        $validator = new Validator($requestData);
        $validator->rule('required', ['username', 'newPassword', 'newPasswordVerify']);
        $validator->rule('email', 'username')->message('Username must be a valid email address');

        $validator->rule('regex', 'newPassword', '/[A-Z]/')->message('uppercase letter');
        $validator->rule('regex', 'newPassword', '/[a-z]/')->message('lowercase letter');
        $validator->rule('regex', 'newPassword', '/\d/')->message('one numeric');
        $validator->rule('regex', 'newPassword', '/[@$!%*?&]/')->message("special character");
        $validator->rule('lengthBetween', 'newPassword', 8, 32)->message('and between 8 and 32 characters long');

        $validator->rule('equals', 'newPasswordVerify', 'newPassword')->message("Passwords don't match");

        $isValid = $validator->validate();

        if (!$isValid) {
            $errors = $validator->errors();
            throw new HttpException($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->username = $requestData['username'];
        $this->password = $requestData['newPassword'];
    }
}
