<?php

namespace Khafidprayoga\PhpMicrosite\Views;

class Home
{
    private static string $title = "X MicroSite / Home";

    private static string $tagline = "Welcome to X MicroSite!";
    private static string $description = "X is an simple social media platform to demonstrate my experience as backend developer.";

    public static function render()
    {
        $title = self::$title;
        $tagline = self::$tagline;
        $description = self::$description;

        echo <<<RENDER
        <html lang="id">
        <head>
            <title> {$title} </title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta charset="utf-8">
            <link rel="stylesheet" href="/styles.css">
        
            <!--  fonts -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Merriweather+Sans:ital,wght@0,300..800;1,300..800&family=Rasa:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
            <!--  fonts -->
            
        </head>
        <body>
        <main class="flex flex-col justify-center items-center mt-[50vh]">
            <h1 class="text-blue-700 text-4xl font-libreBaskerville font-[500]">{$tagline}</h1>
            <h2 class="text-xl font-rasa ">{$description}</h2>
            <!--     Menu CTA       -->
            <div class="mt-5">
                    <ul id="cta-menu-onboarding">
                        <li>
                            <a href="#">
                                Feeds
                            </a>
                        </li>
                         <li>
                            <a href="#">
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Register
                            </a>
                        </li>
                    </ul>
            </div>
                
        </main>
        </body>
        </html>
RENDER;
    }
}
