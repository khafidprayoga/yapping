<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Carbon\Carbon;

class JwtClaimsDTO
{
    private ?int $userId;
    private ?string $userFullName;
    private ?string $jti; // Make jti nullable
    private ?Carbon $issuedAt;
    private ?Carbon $expiresAt;
    private ?Carbon $notBeforeTimeAt;

    public function __construct(array $decodedClaims)
    {
        // Initialize all properties, even if the key is missing in $decodedClaims
        $this->userId = $decodedClaims['sub'] ?? null;
        $this->userFullName = $decodedClaims['name'] ?? null;
        $this->jti = $decodedClaims['jti'] ?? null;
        $this->issuedAt = isset($decodedClaims['iat'])
            ? Carbon::createFromTimestamp($decodedClaims['iat'])
            : null;
        $this->expiresAt = isset($decodedClaims['exp'])
            ? Carbon::createFromTimestamp($decodedClaims['exp'])
            : null;
        $this->notBeforeTimeAt = isset($decodedClaims['nbf'])
            ? Carbon::createFromTimestamp($decodedClaims['nbf'])
            : null;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUserFullName(): ?string
    {
        return $this->userFullName;
    }

    public function getJti(): ?string
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

    public function getNotBeforeTimeAt(): ?Carbon
    {
        return $this->notBeforeTimeAt;
    }

    public static function toArray(JwtClaimsDTO $claimsDTO): array
    {
        $arr = [];
        if ($claimsDTO->getUserId()) {
            $arr['sub'] = $claimsDTO->getUserId();
        }

        if ($claimsDTO->getJti()) {
            $arr['jti'] = $claimsDTO->getJti();
        }
        if ($claimsDTO->getIssuedAt()) {
            $arr['iat'] = $claimsDTO->getIssuedAt()->timestamp;
        }
        if ($claimsDTO->getExpiresAt()) {
            $arr['exp'] = $claimsDTO->getExpiresAt()->timestamp;
        }
        if ($claimsDTO->getNotBeforeTimeAt()) {
            $arr['nbf'] = $claimsDTO->getNotBeforeTimeAt()->timestamp;
        }
        if ($claimsDTO->getUserFullName()) {
            $arr['name'] = $claimsDTO->getUserFullName();
        }

        return $arr;
    }

    public function isAccessToken(): bool
    {
        return $this->jti === null;
    }
}
