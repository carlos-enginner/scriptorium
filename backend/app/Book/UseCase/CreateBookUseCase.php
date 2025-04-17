<?php

namespace App\Book\UseCase;

use App\Book\Domain\Repository\BookRepositoryInterface;

class CreateBookUseCase
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository
    ) {}

    public function execute(array $data)
    {
        $book = $this->bookRepository->create($data);

        $this->bookRepository->attachAuthors($book->id, $data["authors"]);

        $this->bookRepository->attachSubjects($book->id, $data["subjects"]);

        return $book;
    }
}
