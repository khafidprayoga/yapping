<?php

namespace Khafidprayoga\PhpMicrosite\Services;

use Khafidprayoga\PhpMicrosite\Models\DTO\AuthDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\RefreshSessionRequestDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\TokenDTO;

interface AuthServiceInterface
{
    // returns jwt token string
    public function login(string $username, string $password): TokenDTO;

    public function logout(string $jwtToken): bool;

    public function refresh(RefreshSessionRequestDTO $req): TokenDTO;
}
