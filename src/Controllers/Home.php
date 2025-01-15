<?php

namespace Khafidprayoga\PhpMicrosite\Controllers;

class Home extends Main
{
    public function index(): void
    {
         $this->render('Home.twig', [
            "title" => "X Microsite / Home",
            "tagline" => "Welcome to X Microsite",
            "description" => "X is an simple social media platform to demonstrate my experience as backend developer.",
        ]);
    }
}