<?php

require __DIR__ . '/vendor/autoload.php';


use Khafidprayoga\PhpMicrosite\Models\Entities;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public string $fullName,
        public string $username,
        public string $password,
    )
    {
    }
}


$userData = UserData::from([
    "fullName" => "John Doe",
    "username" => "johndoe",
    "password" => "supersecretpassword",
]);

var_dump($userData);