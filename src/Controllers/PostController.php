<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Exception;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Models\DTO\JwtClaimsDTO;
use Khafidprayoga\PhpMicrosite\Utils\Pagination;
use MongoDB\Driver\Server;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class PostController extends InitController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(ServerRequestInterface $request): void
    {
        try {
            $paginator = new Pagination($request->getQueryParams());

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

    public function actionGetUserById(ServerRequestInterface $ctx, int $postId): void
    {
        try {
            $claims =  $ctx->getAttribute("claims");
            $parsedClaims = new JwtClaimsDTO($claims);

            $post = $this->postService->getPostById($postId);
            $this->render(
                "Feed/DetailFeed",
                [
                    "post" => $post,
                    'claims' => $parsedClaims,
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
