<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Khafidprayoga\PhpMicrosite\Models\Entities;

class PostController extends InitController
{
    public function actionGetUserById(int $postId)
    {
        $post = $this->postService->getPostById($postId);
        $this->render(
            "Feed/DetailFeed",
            [
                "post" => $post
            ]
        );
    }
}
