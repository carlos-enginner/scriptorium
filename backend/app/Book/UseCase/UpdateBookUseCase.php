<?php

namespace App\Book\UseCase;

use App\Book\Domain\Repository\BookRepositoryInterface;
use App\Book\UseCase\DTO\BookDTO;

class UpdateBookUseCase
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository
    ) {}

    public function execute(int $id, BookDTO $dto)
    {
        $book = $this->bookRepository->update($id, $dto);

        $this->bookRepository->attachAuthors($book->id, $dto->authors);

        $this->bookRepository->attachSubjects($book->id, $dto->subjects);

        return $book;
    }
}
