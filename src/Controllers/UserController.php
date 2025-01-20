<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Khafidprayoga\PhpMicrosite\Models\DTO\UserDTO;
use Khafidprayoga\PhpMicrosite\Utils\Pagination;
use Symfony\Component\HttpFoundation\Response;

class UserController extends InitController
{
    public function actionCreateUser(): void
    {
        try {
            $jsonBody = $this->getJsonBody();
            $request = new UserDTO($jsonBody);

            $user = $this->userService->createUser($request);
            var_dump($user);
        } catch (Exception $exception) {
            $this->render("Fragment/Exception", [
                "error_title" => "Get Feeds error",
                "error_message" => $exception->getMessage(),
                "menu" => "Feed",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index()
    {
    }
}
