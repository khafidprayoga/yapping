<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Carbon\Carbon;
use Khafidprayoga\PhpMicrosite\Commons\HttpException;
use Khafidprayoga\PhpMicrosite\Models\DTO\LoginRequestDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\RefreshSessionRequestDTO;
use Khafidprayoga\PhpMicrosite\Models\DTO\UserDTO;
use Khafidprayoga\PhpMicrosite\Utils\Cookies;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class UserController extends InitController
{
    public function actionCreateUser(ServerRequestInterface $request): void
    {
        try {
            $data = $this->getFormData($request);
            $userRegister = new UserDTO($data);


            $userExist = $this->userService->getUserByUsername($userRegister->username);
            if (!empty($userExist)) {
                throw new HttpException("Sorry, user with this email already exist", Response::HTTP_BAD_REQUEST);
            }

            // creating user
            $this->userService->createUser($userRegister);

            $credentials = $this->authService->login($userRegister->username, $userRegister->password);
            setcookie("accessToken", $credentials->getAccessToken(), Cookies::formatSettings(
                appConfig: APP_CONFIG,
                expiresIn: $credentials->getAccessTokenExpiresAt(),
                path: '/',
            ));

            setcookie("refreshToken", $credentials->getRefreshToken(), Cookies::formatSettings(
                appConfig: APP_CONFIG,
                expiresIn: $credentials->getRefreshTokenExpiresAt(),
                path: '/',
            ));

            $this->redirect('/feeds');
        } catch (HttpException $err) {
            $this->responseJson($err, null, $err->getCode());
        }
    }

    public function signIn(ServerRequestInterface $request): void
    {
        // check token
        $isAuthenticated = $this->authCheck($request);
        if ($isAuthenticated) {
            $this->redirect('/feeds');
        }

        // if user doesnt has token or token expired render login page
        $this->render('User/SignIn', [
            'actionUrl' => '/users/login',
            'httpMethod' => 'POST',
        ]);
    }

    public function signUp(ServerRequestInterface $request): void
    {
        // check token
        $isAuthenticated = $this->authCheck($request);
        if ($isAuthenticated) {
            $this->redirect('/feeds');
        }

        // if error instance of claims validation force render the template
        $this->render('User/SignUp', [
            'actionUrl' => '/users/register',
        ]);
    }

    public function actionAuthenticate(ServerRequestInterface $request): void
    {
        try {
            $formData = $this->getFormData($request);
            $hasNext = $formData['next'] ?? null;
            $loginRequest = new LoginRequestDTO($formData);

            $credentials = $this->authService->login($loginRequest->getUsername(), $loginRequest->getPassword());
            setcookie("accessToken", $credentials->getAccessToken(), Cookies::formatSettings(
                appConfig: APP_CONFIG,
                expiresIn: $credentials->getAccessTokenExpiresAt(),
                path: '/',
            ));

            setcookie("refreshToken", $credentials->getRefreshToken(), Cookies::formatSettings(
                appConfig: APP_CONFIG,
                expiresIn: $credentials->getRefreshTokenExpiresAt(),
                path: '/',
            ));

            if (!is_null($hasNext)) {
                $this->redirect(urldecode($hasNext));
            }

            $this->redirect('/feeds');
        } catch (HttpException $err) {
            $this->responseJson($err, null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function actionLogout(): void
    {
        // check token
        $accessToken = $_COOKIE['accessToken'] ?? null;
        $refreshToken = $_COOKIE['refreshToken'] ?? null;
        if (is_null($accessToken) && is_null($refreshToken)) {
            $this->redirect('/signin');
        }

        // flush cookies
        $cookiesKey = ['accessToken', 'refreshToken'];
        foreach ($cookiesKey as $key) {
            if ($key === 'refreshToken') {
                $this->authService->logout($_COOKIE["refreshToken"]);
            }

            // set expiring on browser
            setcookie($key, '', Cookies::formatSettings(
                appConfig: APP_CONFIG,
                expiresIn: Carbon::now()->addDays(-30)->timestamp,
                path: '/',
            ));

            // flush on server
            unset($_COOKIE[$key]);
        }

        // redirect to signin page
        $this->redirect('/signin');
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
