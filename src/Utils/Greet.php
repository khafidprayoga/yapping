<?php

namespace Khafidprayoga\PhpMicrosite\Utils;

use Carbon\Carbon;

class Greet
{
    public static function greet(string $name): string
    {
        $hour = Carbon::now()->timezone(APP_CONFIG->serverTimeZone)->hour;
        $greeting = match (true) {
            $hour >= 5 && $hour < 12 => "Good Morning",
            $hour >= 12 && $hour < 15 => "Good Afternoon",
            $hour >= 15 && $hour < 18 => "Good Evening",
            $hour >= 18 && $hour < 22 => "Good Night",
            default => "Sweet Dreams",
        };

        return $greeting .", ". $name;
    }
}
