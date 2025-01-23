<?php

namespace Khafidprayoga\PhpMicrosite\Models\Entities;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use DateTimeImmutable;

#[ORM\Entity]
#[ORM\Table(name: 'sessions')]
#[ORM\Index(name: 'idx_sessions_token', columns: ['jti', 'is_revoked'])]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'jti', type: Types::STRING, length: 36, unique: true, )]
    private string $jti;
    #[ORM\Column(name: 'refresh_token', type: Types::TEXT, nullable: false)]
    private string $refreshToken;

    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[ORM\Column(name: 'expires_at', type: Types::DATETIMETZ_IMMUTABLE)]
    private DateTimeImmutable $expiresAt;
    #[ORM\Column(name: 'is_revoked', type: Types::BOOLEAN, options: ['default' => false])]
    private bool $isRevoked;

    public function isRevoked(): bool
    {
        return $this->isRevoked;
    }

    public function getJti(): string
    {
        return $this->jti;
    }
}
