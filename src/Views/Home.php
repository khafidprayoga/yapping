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

        return <<<RENDER
        <html lang="id">
        <head>
            <title> {$title} </title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta charset="utf-8">
            <style>
                main {
                    border: 1px solid black;
                }
                
                main {
                    display: flex;
                    flex-direction: column;
                    flex-wrap: wrap;
                    align-content: center;
                    align-items: center;
                    width: 50%;
                    max-width: 720px;
                    height: 75vh;
                    margin: 0 auto;
                    padding: 1em 1em;
                }
            </style>
        </head>
        <body>
        <main>
            <h1>{$tagline}</h1>
            <h2>{$description}</h2>
        </main>
        </body>
        </html>
RENDER;
    }
}
