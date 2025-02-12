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
use Valitron\Validator;

class UserController extends InitController
{
    private const int PASSWORD_RESET_MAX_ATTEMPT_COUNT = 3;
    private const int PASSWORD_RESET_COOLDOWN_IN_HOURS = 1;

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

    public function actionLogout(ServerRequestInterface $request): void
    {
        // check token
        $isAuthenticated = $this->authCheck($request);
        if (!$isAuthenticated) {
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

    public function resetPassword(ServerRequestInterface $request): void
    {
        // check token
        $isAuthenticated = $this->authCheck($request);
        if ($isAuthenticated) {
            $this->redirect('/feeds');
        }

        session_name('RESETPASS');
        session_start();

        if (!isset($_SESSION['reset_attempts'])) {
            $_SESSION['reset_attempts'] = 1;
            $_SESSION['reset_attempts_first_time'] = Carbon::now()->timestamp;
        }

        $queryParams = $this->getQueryParameters($request);
        $hasToken = false;

        if (isset($queryParams['token'])) {
            try {
                $claims = $this->authService->validate($queryParams['token']);
                if ($claims !== null) {
                    $validator = new Validator([
                        'email' => $claims->getJti(),
                    ]);

                    $validator->rule('email', 'email');
                    if ($validator->validate()) {
                        $hasToken = true;
                    }
                }
            } catch (HttpException) {
                //ignore invalid malformed jwt to prevent panic
            }
        }


        // if user doesnt has token or token expired render login page
        $this->render('User/Forgot', [
            'actionUrl' => '/users/forgot_password',
            'hasToken' => $hasToken,
        ]);
    }

    public function actionResetPassword(ServerRequestInterface $request): void
    {
        try {
            $isAuthenticated = $this->authCheck($request);
            if ($isAuthenticated) {
                $this->redirect('/feeds');
            }


            // get form
            $formData = $this->getFormData($request);
            if (!isset($formData['username'])) {
                throw new HttpException('username field is required', Response::HTTP_BAD_REQUEST);
            }

            if (!isset($_COOKIE['RESETPASS'])) {
                $this->redirect('/users/forgot');
            }

            session_name('RESETPASS');
            session_start();
            if ($_SESSION['reset_attempts'] >= self::PASSWORD_RESET_MAX_ATTEMPT_COUNT) {
                $since = Carbon::createFromTimestamp($_SESSION['reset_attempts_first_time'])->setTimezone(APP_CONFIG->serverTimeZone);

                // check from limit time
                if ($since->diffInHours(Carbon::now()) < self::PASSWORD_RESET_COOLDOWN_IN_HOURS) {
                    $msg = sprintf('Too many reset password request attempt, please try again after %s', $since->addHour(self::PASSWORD_RESET_COOLDOWN_IN_HOURS)->toDateTimeString());
                    throw new HttpException($msg, Response::HTTP_INTERNAL_SERVER_ERROR);
                } else {
                    // reset after the cooldown lease
                    $_SESSION['reset_attempts'] = 0;
                    $_SESSION['reset_attempts_first_time'] = Carbon::now()->timestamp;
                }
            }

            // increase limiter
            $_SESSION['reset_attempts']++;

            // generate jwt token for reset password
            $resetToken = $this->authService->generateResetToken($formData['username']);

            // stub (as mailbox)
            $this->log->info('Reset password request', [
                'email' => $formData['username'],
                'resetToken' => $resetToken['token'],
                'uri' => APP_CONFIG->appDomain . "/users/forgot?token=" . $resetToken['token'],
                'text' => 'You are requested to reset your password, if its an mistake, ignore this email',
            ]);

            // only set response ok
            $this->responseJson(null, 'success sent reset password to mailbox', Response::HTTP_OK);
        } catch (HttpException $err) {
            $this->responseJson($err, null, $err->getCode());
        }
    }
}
