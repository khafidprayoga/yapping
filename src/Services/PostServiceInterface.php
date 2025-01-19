<?php

namespace Khafidprayoga\PhpMicrosite\Services;

use Khafidprayoga\PhpMicrosite\Models\Entities\Post;
use Khafidprayoga\PhpMicrosite\Utils\Pagination;

interface PostServiceInterface
{
    public function createNewPost(): Post;

    public function getPostById(int $id): array;

    public function getPosts(Pagination $pagination): array;

}
