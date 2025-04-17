<?php

namespace App\Book\UseCase;

use App\Book\Domain\Repository\BookRepositoryInterface;

class UpdateBookUseCase
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository
    ) {}

    public function execute(int $id, array $data)
    {
        $book = $this->bookRepository->update($id, $data);

        $this->bookRepository->attachAuthors($book->id, $data["authors"]);

        $this->bookRepository->attachSubjects($book->id, $data["subjects"]);

        return $book;
    }
}
