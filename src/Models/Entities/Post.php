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
    private int|null $id = null;
    #[ORM\Column(name: 'title', type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(name: 'content', type: 'text', nullable: false)]
    private string $content;

    #[ORM\Column(name: 'user_id', type: 'integer')]
    private int $userId;

    #[ORM\Column(name: 'created_at', type: 'datetimetz', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private int $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetimetz', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private int $updatedAt;

    #[ORM\Column(name: 'is_deleted', type: 'boolean', options: ['default' => false])]
    private bool $isDeleted;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $author;

    public function getAuthor(): User
    {
        return $this->author;
    }

}