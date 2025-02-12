<?php

use Carbon\Carbon;

require_once "vendor/autoload.php";

$since = Carbon::createFromTimestamp(1739334460);
$at = Carbon::now()->addHours(0);
$diff = $since->diffInHours($at);

echo $since . PHP_EOL;
echo $at . PHP_EOL;


echo $diff . PHP_EOL;

