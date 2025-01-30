<?php

namespace Khafidprayoga\PhpMicrosite\Services;

use Khafidprayoga\PhpMicrosite\Models\DTO\PostingRequestDTO;
use Khafidprayoga\PhpMicrosite\Utils\Pagination;

interface PostServiceInterface
{
    public function createNewPost(PostingRequestDTO $request): array;

    public function getPostById(int $id): array;

    public function getPosts(Pagination $pagination): array;

}
