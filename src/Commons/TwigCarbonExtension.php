<?php

namespace Khafidprayoga\PhpMicrosite\Commons;

use Carbon\Carbon;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigCarbonExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('ago', [$this, 'timeAgo']),
        ];
    }

    public function timeAgo($datetime)
    {
        return Carbon::parse($datetime)->diffForHumans();
    }
}
