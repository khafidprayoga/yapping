<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use Spatie\LaravelData\Data;

class PostData extends Data
{
    public function __construct(
        public int       $id,
        public string    $title,
        public string    $content,
        public int       $userId,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public bool      $isDeleted,
        public ?User     $author,
    ) {
    }

}
