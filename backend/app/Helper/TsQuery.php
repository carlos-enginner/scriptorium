<?php

declare(strict_types=1);

namespace App\Helper;

class TsQuery
{
    public static function tokenizer(string $input): string
    {
        return preg_replace('/\s+/u', '&', $input);
    }
}
