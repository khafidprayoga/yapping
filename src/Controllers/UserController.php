<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Doctrine\DBAL\Exception;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Models\DTO\AuthenticateDTO;
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
        } catch (HttpException $exception) {
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

    public function actionAuthenticate(): void
    {
        try {
            $jsonBody = $this->getJsonBody();
            $request = new AuthenticateDTO($jsonBody);

            $credentials = $this->authService->login($request->getUsername(), $request->getPassword());

            $this->responseJson(null, $credentials, Response::HTTP_OK);
        } catch (HttpException $err) {
            $this->responseJson($err, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
