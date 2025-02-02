<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Exception;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Models\DTO\FeedsRequestDTO;
use Khafidprayoga\PhpMicrosite\Utils\Greet;
use Khafidprayoga\PhpMicrosite\Utils\Pagination;
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
            $paginator = new FeedsRequestDTO($request->getQueryParams());
            $claims = $this->getClaims($request);

            $posts = $this->postService->getPosts($paginator);
            $totalPages = $paginator->getTotalPages() ?? $posts['total_pages'];
            $totalItems = $paginator->getTotalItems() ?? $posts['total_items'];

            $authors = $this->userService->getUsers($paginator, false);
            $showPrevBtn = $paginator->getPage() - 1 > 0;


            $showNextBtn = count($posts['data']) - $paginator->getPageSize() === 0 && $paginator->getPage() < $totalPages;

            $this->log->debug('authors', [
                'authors' => $authors,
            ]);
            $this->render(
                "Feed/Feeds",
                [
                    "page" => $paginator->getPage(),
                    'paginator' => $paginator,
                    'totalPages' => $totalPages,
                    'totalItems' => $totalItems,

                    'posts' => $posts['data'],
                    'authors' => $authors,
                    'greet' => "Signed as " . $claims->getUserFullName(),

                    'showPrevBtn' => $showPrevBtn,
                    'showNextBtn' => $showNextBtn
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

            $postAuthor = $postDetails['post']['author']['id'] === $claims->getUserId();

            $this->render(
                "Feed/DetailFeed",
                [
                    'previousId' => $postDetails['previousPostId'],
                    'nextId' => $postDetails['nextPostId'],
                    'paginator' => $paginator,
                    'post' => $postDetails['post'],
                    'greet' => "Signed as " . $claims->getUserFullName(),
                    'isAuthor' => $postAuthor,
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

    public function actionDeletePostById(ServerRequestInterface $ctx, int $postId): void
    {
        $claims = $this->getClaims($ctx);
        $this->postService->deletePostById($claims->getUserId(), $postId);

        $this->redirect("/feeds");
    }
}
