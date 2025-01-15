<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

class Home extends InitController
{
    public function index(): void
    {
        $this->render('Home', [
            "title" => "X Microsite / Home",
            "tagline" => "Welcome to X Microsite",
            "description" => "X is an simple social media platform to demonstrate my experience as backend developer.",
            "cta" => [
                [
                    "/feeds" => "Feed"
                ],
                [
                    "/login" => "Login"
                ],
                [
                    "/register" => "Register"
                ]
            ]
        ]);
    }
}
