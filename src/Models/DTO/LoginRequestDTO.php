<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Valitron\Validator;

class LoginRequestDTO extends BaseRequestDTO
{
    private string $username;
    private string $password;
    private int $passwordMinLength = 8;
    private int $passwordMaxLength = 32;

    /**
     * @throws HttpException
     */
    public function __construct(array $requestData)
    {
        $validator = new Validator($requestData);
        $validator->rule('required', ['username', 'password']);
        $validator->rule('email', 'username');

        $validator->rule('regex', 'password', '/[A-Z]/')->message('uppercase letter, ');
        $validator->rule('regex', 'password', '/[a-z]/')->message('lowercase letter, ');
        $validator->rule('regex', 'password', '/\d/')->message('one numeric, ');
        $validator->rule('regex', 'password', '/[@$!%*?&]/')->message("special character, ");
        $validator->rule('lengthBetween', 'password', $this->passwordMinLength, $this->passwordMaxLength)->message('and between 8 and 32 characters long');

        $isValid = $validator->validate();
        if (!$isValid) {
            foreach ($validator->errors() as $field => $errors) {
                if ($field === 'password') {
                    throw new HttpException('Password must be contains ' . implode('', $errors), Response::HTTP_BAD_REQUEST);
                }
            }
        }

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
