<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

class User extends InitController
{
    public function actionCreateUser(): void
    {
        // validate request

        // checks if username exist
        $this->userService->getUserByUsername();
        // todo create user
        $this->userService->createUser();
    }

    public function index()
    {
    }
}
