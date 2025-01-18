<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PostController extends InitController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function actionGetUserById(int $postId): void
    {
        try {
            $post = $this->postService->getPostById($postId);
            $this->render(
                "Feed/DetailFeed",
                [
                    "post" => $post
                ],
            );
        } catch (Exception $exception) {
            $this->render("Fragment/Exception", [
                "error_title" => "Feed details not found",
                "error_message" => $exception->getMessage(),
                "menu" => "Feed",
            ], Response::HTTP_NOT_FOUND);
        }

    }
}
