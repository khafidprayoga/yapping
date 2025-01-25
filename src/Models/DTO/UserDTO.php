<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Valitron\Validator;
use Exception;

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
        $validator->rule('email', 'username');

        $validator
            ->rule('lengthBetween', 'password', 8, 32)->message('Password must be between 8 and 32 characters long')
            ->rule('regex', 'password', '/[A-Z]/')  // Must contain at least one uppercase letter
            ->rule('regex', 'password', '/[a-z]/')  // Must contain at least one lowercase letter
            ->rule('regex', 'password', '/\d/')     // Must contain at least one number
            ->rule('regex', 'password', '/[@$!%*?&]/'); // Must contain at least one special character

        $validator->rule('equals', 'password', 'passwordVerify')->message("Passwords don't match");

        $isValid = $validator->validate();
        if (!$isValid) {
            foreach ($validator->errors() as $field => $messages) {
                throw new HttpException($messages[0], Response::HTTP_BAD_REQUEST);
            }
        }
        $this->fullName = $requestData['fullName'];
        $this->username = $requestData['username'];
        $this->password = $requestData['password'];
    }
}
