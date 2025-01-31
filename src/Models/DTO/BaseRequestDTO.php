<?php

namespace Khafidprayoga\PhpMicrosite\Models\DTO;

use HTMLPurifier;

class BaseRequestDTO
{
    protected function sanitize(string $data): mixed
    {
        return HtmlPurifier::instance()->purify($data);
    }
}
