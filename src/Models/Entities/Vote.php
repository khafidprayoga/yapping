<?php

namespace Khafidprayoga\PhpMicrosite\Models\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity]
#[ORM\Table(name: 'votes')]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'votes')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;
    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'votes')]
    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id', nullable: false)]
    private Post $post;

    #[ORM\Column(name: 'created_at', type: 'datetimetz', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private int $createdAt;

}
