<?php

namespace Khafidprayoga\PhpMicrosite\Services;

use Khafidprayoga\PhpMicrosite\Models\DTO\FeedsRequestDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\PostingRequestDTO;
use Khafidprayoga\PhpMicrosite\Utils\Pagination;

interface PostServiceInterface
{
    public function createNewPost(PostingRequestDTO $request): array;

    public function getPostById(Pagination $paginator, int $id): array;

    public function getPosts(FeedsRequestDTO $pagination): array;

    public function deletePostById(int $userId, int $postId): void;


}
