<?php

namespace App\Book\UseCase\DTO;

class BookDTO
{
    public function __construct(
        public ?int $id = null,
        public ?string $title = null,
        public ?string $publisher = null,
        public ?int $edition = null,
        public ?int $publicationYear = null,
        public ?float $price = null,
        public array $authors = [],
        public array $subjects = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'] ?? null,
            publisher: $data['publisher'] ?? null,
            edition: $data['edition'] ?? null,
            publicationYear: $data['publication_year'] ?? null,
            price: $data['price'] ?? null,
            authors: $data['authors'] ?? [],
            subjects: $data['subjects'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'publisher' => $this->publisher,
            'edition' => $this->edition,
            'publication_year' => $this->publicationYear,
            'price' => $this->price,
            'authors' => $this->authors,
            'subjects' => $this->subjects,
        ];
    }
}
