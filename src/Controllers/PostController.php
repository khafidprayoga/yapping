<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Khafidprayoga\PhpMicrosite\Models\Entities;
use Symfony\Component\HttpFoundation\Response;

class PostController extends InitController
{
    public function actionGetUserById(int $postId)
    {
        try {
            $post = $this->postService->getPostById($postId);
            $this->render(
                "Feed/DetailFeed",
                [
                    "post" => $post
                ]
            );
        } catch (\Exception $exception) {
            http_response_code(Response::HTTP_NOT_FOUND);
            $this->render("Fragment/Exception", [
                "error_title" => "Feed details not found",
                "error_message" => $exception->getMessage(),
                "menu" => "Feed",
            ]);
        }

    }
}
