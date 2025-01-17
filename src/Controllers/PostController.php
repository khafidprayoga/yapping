<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Khafidprayoga\PhpMicrosite\Models\Entities;

class PostController extends InitController
{
    public function actionGetUserById(int $postId)
    {
        $post = $this->postService->getPostById($postId);
        $postTitle = $this
            ->getEntityFieldValue(Entities\Post::class, $post, 'title');

        $this->render(
            "Feed/DetailFeed",
            [
                "title" => "X Microsite / Feed \"$postTitle\" ",
            ]
        );
    }
}
