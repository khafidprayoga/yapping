<?php

namespace Khafidprayoga\PhpMicrosite\UseCases;

use Khafidprayoga\PhpMicrosite\Models\Entities\Post;
use Khafidprayoga\PhpMicrosite\Services\PostService;

class PostServiceImpl extends InitUseCase implements PostService
{
    public function createNewPost(): Post
    {
        // TODO: Implement createNewPost() method.
    }

    public function getPostById(int $id): Post
    {
        $repo = $this->entityManager->getRepository(Post::class);
        $post = $repo->findOneBy(['id' => $id]);
        if (!$post) {
            throw new \Exception("Post with id {$row['id']} not found");
        }

        return $post;
    }
}
