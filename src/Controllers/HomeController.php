<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

use Khafidprayoga\PhpMicrosite\Utils\Greet;
use Psr\Http\Message\ServerRequestInterface;

class HomeController extends InitController
{
    public function index(ServerRequestInterface $request): void
    {
        $claims = $this->getClaims($request);
        $tagline = "Welcome to X Microsite";
        if ($claims->getUserFullName() !== null) {
            $greeter = Greet::greet($claims->getUserFullName());
            $tagline = $greeter . "! and Welcome  to X Microsite";
        }
        $this->render('Home', [
            "title" => "X Microsite / Home",
            "tagline" => $tagline,
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
