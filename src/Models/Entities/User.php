<?php

namespace Khafidprayoga\PhpMicrosite\Models\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
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

    #[ORM\Column(name: 'created_at', type: 'datetimetz')]
    private int $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetimetz')]
    private int $updatedAt;
}