<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Exception;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Models\DTO\JwtClaimsDTO;
use Khafidprayoga\PhpMicrosite\Utils\Greet;
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
            $claims = $this->getClaims($request);

            $posts = $this->postService->getPosts($paginator);
            $this->render(
                "Feed/Feeds",
                [
                    "posts" => $posts,
                    'greet' => "Signed as " . $claims->getUserFullName(),
                ],
            );
        } catch (HttpException $exception) {
            $this->render("Fragment/Exception", [
                "error_title" => "Get Feeds error",
                "error_message" => $exception->getMessage(),
                "menu" => "Feed",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function actionGetPostById(ServerRequestInterface $ctx, int $postId): void
    {
        try {
            $claims = $this->getClaims($ctx);

            $post = $this->postService->getPostById($postId);
            $this->render(
                "Feed/DetailFeed",
                [
                    "post" => $post,
                    'greet' => "Signed as " . $claims->getUserFullName(),
                ],
            );
        } catch (HttpException $exception) {
            $claims = $this->getClaims($ctx);

            $this->render("Fragment/Exception", [
                "error_title" => "Failed get feed",
                "error_message" => $exception->getMessage(),
                'greet' => Greet::greet($claims->getUserFullName()),
            ], Response::HTTP_NOT_FOUND);
        }

    }
}
