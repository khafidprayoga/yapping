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
                    "page" => $paginator->getPage(),
                    "pageSize" => $paginator->getPageSize(),
                    "search" => $paginator->getSearch(),
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
            $paginator = new Pagination($ctx->getQueryParams());
            $claims = $this->getClaims($ctx);

            $postDetails = $this->postService->getPostById($paginator, $postId);

            $this->render(
                "Feed/DetailFeed",
                [
                    'previousId' => $postDetails['previousPostId'],
                    'nextId' => $postDetails['nextPostId'],
                    'paginator' => $paginator,
                    'post' => $postDetails['post'],
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
