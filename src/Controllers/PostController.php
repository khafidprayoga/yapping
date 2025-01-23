<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Exception;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Utils\Pagination;
use Symfony\Component\HttpFoundation\Response;

class PostController extends InitController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): void
    {
        try {
            $paginator = new Pagination($this->request->getQueryParams());

            $posts = $this->postService->getPosts($paginator);
            $this->render(
                "Feed/Feeds",
                ["posts" => $posts,],
            );
        } catch (HttpException $exception) {
            $this->render("Fragment/Exception", [
                "error_title" => "Get Feeds error",
                "error_message" => $exception->getMessage(),
                "menu" => "Feed",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        } catch (HttpException $exception) {
            $this->render("Fragment/Exception", [
                "error_title" => "Failed get feed",
                "error_message" => $exception->getMessage(),
                "menu" => "Feed",
            ], Response::HTTP_NOT_FOUND);
        }

    }
}
