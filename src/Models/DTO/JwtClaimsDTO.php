<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Carbon\Carbon;

class JwtClaimsDTO
{
    private int $userId;
    private ?string $userFullName;
    private string $jti;
    private Carbon $issuedAt;
    private Carbon $expiresAt;
    private ?Carbon $notBeforeTimeAt;

    public function __construct(array $decodedClaims)
    {
        if (array_key_exists('sub', $decodedClaims)) {
            $this->userId = $decodedClaims['sub'];
        }

        if (array_key_exists('name', $decodedClaims)) {
            $this->userFullName = $decodedClaims['name'];
        }

        if (array_key_exists('jti', $decodedClaims)) {
            $this->jti = $decodedClaims['jti'];
        }

        if (array_key_exists('iat', $decodedClaims)) {
            $parsedTime = Carbon::createFromTimestamp($decodedClaims['iat']);
            $this->issuedAt = $parsedTime;
        }

        if (array_key_exists('exp', $decodedClaims)) {
            $parsedTime = Carbon::createFromTimestamp($decodedClaims['exp']);
            $this->expiresAt = $parsedTime;
        }

        if (array_key_exists('nbf', $decodedClaims)) {
            $parsedTime = Carbon::createFromTimestamp($decodedClaims['nbf']);
            $this->notBeforeTimeAt = $parsedTime;
        }
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUserFullName(): string
    {
        return $this->userFullName;
    }

    public function getJti(): string
    {
        return $this->jti;
    }

    public function getIssuedAt(): Carbon
    {
        return $this->issuedAt;
    }

    public function getExpiresAt(): Carbon
    {
        return $this->expiresAt;
    }

    public function getNotBeforeTimeAt(): Carbon
    {
        return $this->notBeforeTimeAt;
    }
}
