<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Carbon\Carbon;

class TokenDTO
{
    private string $accessToken;
    private ?string $refreshToken;
    private ?int $accessTokenExpiresAt;
    private ?int $refreshTokenExpiresAt;


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

    public function setAccessTokenExpiresAt(int $expiresAt): void
    {
        $this->accessTokenExpiresAt = $expiresAt;
    }

    public function setRefreshTokenExpiresAt(int $expiresAt): void
    {
        $this->refreshTokenExpiresAt = $expiresAt;
    }

    public function getAccessTokenExpiresAt(): int
    {
        return $this->accessTokenExpiresAt;
    }

    public function getRefreshTokenExpiresAt(): int
    {
        return $this->refreshTokenExpiresAt;
    }
}
