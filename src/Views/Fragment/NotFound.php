<?php

namespace Khafidprayoga\PhpMicrosite\Views\Fragment;

class NotFound
{
    private static string $title = "X MicroSite / Not Found";
    private static string $tagline = "X MicroSite ";


    public static function render()
    {
        $title = self::$title;
        $tagline = self::$tagline;

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
            <h1>$tagline</h1>
            <h2>404</h2>
            <h3>Something went wrong, you are visiting invalid route.</h3>
        </main>
        </body>
        </html>
RENDER;
    }
}
