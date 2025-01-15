<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

class Post extends InitController
{
    public function actionGetUserById(int $postId)
    {
        $post = $this->postService->getPostById($postId);
        var_dump($post);
    }
}
