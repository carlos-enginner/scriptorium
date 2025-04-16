<?php

declare(strict_types=1);

namespace App\Author\Domain\Entity;

use Carbon\Carbon;

class Author
{
    public function __construct(
        public readonly int $id,
        public string $name,
        public readonly Carbon $createdAt,
        public readonly Carbon $updatedAt,
    ) {}
}
