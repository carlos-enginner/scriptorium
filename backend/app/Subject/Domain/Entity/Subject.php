<?php

namespace App\Subject\Domain\Entity;

use Carbon\Carbon;

class Subject
{
    public function __construct(
        public readonly ?int $id,
        public string $description,
        public readonly ?Carbon $createdAt = null,
        public readonly ?Carbon $updatedAt = null
    ) {}
}
