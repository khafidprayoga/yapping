<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

class TokenDTO
{
    private string $accessToken;
    private ?string $refreshToken;

    public function __construct(string $accessToken, ?string $refreshToken = null)
    {
        $this->accessToken = $accessToken;
        if ($refreshToken) {
            $this->refreshToken = $refreshToken;
        }
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
