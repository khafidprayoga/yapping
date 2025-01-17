<?php

namespace Khafidprayoga\PhpMicrosite\Services;

use Khafidprayoga\PhpMicrosite\Models\Entities\Post;

interface PostServiceInterface
{
    public function createNewPost(): Post;

    public function getPostById(int $id): Post|array;

}
