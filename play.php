<?php

require_once "vendor/autoload.php";
$date = '2025-02-27';

$dt = \Carbon\Carbon::createFromDate($date);
echo $dt->format('Y/m/d');