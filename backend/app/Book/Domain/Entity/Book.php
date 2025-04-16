<?php

namespace App\Book\Domain\Entity;

class Book
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $title,
        public readonly string $publisher,
        public readonly string $edition,
        public readonly int $publicationYear,
        public readonly float $price,
        public readonly ?\DateTimeInterface $createdAt = null,
        public readonly ?\DateTimeInterface $updatedAt = null,
    ) {}
}
