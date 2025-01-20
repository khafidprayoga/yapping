<?php

namespace Khafidprayoga\PhpMicrosite\Models\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'username', columns: ['username'])]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'full_name', type: 'string', length: 255)]
    private string $fullName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $username;
    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(name: 'created_at', type: 'datetimetz', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetimetz', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $updatedAt;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'user')]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'user_id', nullable: false)]
    private Collection $posts;
}
