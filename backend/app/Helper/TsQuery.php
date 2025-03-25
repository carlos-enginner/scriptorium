<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Helper;

class TsQuery
{
    public static function tokenizer(string $input): string
    {
        return preg_replace('/\s+/u', '&', $input);
    }
}
