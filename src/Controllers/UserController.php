<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Doctrine\DBAL\Exception;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Models\DTO\LoginRequestDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\RefreshSessionRequestDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\UserDTO;
use Khafidprayoga\PhpMicrosite\Utils\Pagination;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class UserController extends InitController
{
    public function actionCreateUser(ServerRequestInterface $request): void
    {
        try {
            $data = $this->getFormData($request);
            $userRegister = new UserDTO($data);

            // creating user
            $this->userService->createUser($userRegister);

            $token = $this->authService->login($userRegister->username, $userRegister->password);
            $this->responseJson(null, $token);
        } catch (HttpException $exception) {
            $this->render("Fragment/Exception", [
                "error_title" => "Get Feeds error",
                "error_message" => $exception->getMessage(),
                "menu" => "Feed",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function signIn(): void
    {
        try {
            $this->render('User/SignIn', [
                'actionUrl' => '/users/login',
                'httpMethod' => 'POST',
            ]);
        } catch (HttpException $exception) {
            $this->render("Fragment/Exception", [
                "error_title" => "Sign In user error",
                "error_message" => $exception->getMessage(),
                "menu" => "Sign In",
            ]);
        }
    }

    public function signUp(): void
    {
        try {
            $this->render('User/SignUp', [
                'actionUrl' => '/users/register',
                'httpMethod' => 'POST',
            ]);
        } catch (HttpException $exception) {
            $this->render("Fragment/Exception", [
                "error_title" => "Sign In user error",
                "error_message" => $exception->getMessage(),
                "menu" => "Register",
            ]);
        }
    }

    public function actionAuthenticate(ServerRequestInterface $request): void
    {
        try {
            $formData = $this->getFormData($request);
            $loginRequest = new LoginRequestDTO($formData);

            $credentials = $this->authService->login($loginRequest->getUsername(), $loginRequest->getPassword());

            $this->responseJson(null, $credentials, Response::HTTP_OK);
        } catch (HttpException $err) {
            $this->responseJson($err, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function actionLogout(): void
    {
    }

    public function actionRevalidateToken(ServerRequestInterface $req): void
    {
        try {
            $jsonBody = $this->getJsonBody($req);
            $request = new RefreshSessionRequestDTO($jsonBody);

            $token = $this->authService->refresh($request);
            $this->responseJson(null, $token, Response::HTTP_OK);
        } catch (HttpException $err) {
            $this->responseJson($err, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
