<?php


namespace Khafidprayoga\PhpMicrosite\Services;

use Khafidprayoga\PhpMicrosite\Models\Entities\Post;

interface PostService
{
    public function createNewPost(): Post;

}