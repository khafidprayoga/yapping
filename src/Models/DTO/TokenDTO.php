<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Carbon\Carbon;

class TokenDTO
{
    private string $accessToken;
    private ?string $refreshToken;
    private ?int $accessTokenexpiresAt;
    private ?int $refreshTokenexpiresAt;


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
        $this->accessTokenexpiresAt = $expiresAt;
    }

    public function setRefreshTokenExpiresAt(int $expiresAt): void
    {
        $this->refreshTokenexpiresAt = $expiresAt;
    }

    public function getAccessTokenExpiresAt(): int
    {
        return $this->accessTokenexpiresAt;
    }

    public function getRefreshTokenExpiresAt(): int
    {
        return $this->refreshTokenexpiresAt;
    }
}
