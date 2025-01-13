<?php


namespace Khafidprayoga\PhpMicrosite\Models\Entities;

class Post
{
    private int|null $id = null;
    private string $title;
    private string $content;
    private int $createdAt;
    private int $isDeleted;
}