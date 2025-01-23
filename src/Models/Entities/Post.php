<?php

namespace Khafidprayoga\PhpMicrosite\Models\Entities;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'posts')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private int $id;
    #[ORM\Column(name: 'title', type: Types::STRING, length: 255)]
    private string $title;

    #[ORM\Column(name: 'content', type: Types::TEXT, nullable: false)]
    private string $content;

    #[ORM\Column(name: 'user_id', type: Types::INTEGER)]
    private int $userId;

    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIMETZ_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $updatedAt;

    #[ORM\Column(name: 'is_deleted', type: Types::BOOLEAN, options: ['default' => false])]
    private bool $isDeleted;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private ?User $author;


}
