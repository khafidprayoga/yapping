<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

class RefreshSessionRequestDTO
{
    private string $refreshToken;

    public function __construct(array $req)
    {
        $this->refreshToken = $req['refreshToken'];
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
