<?php

namespace App\Book\UseCase;

use App\Book\Domain\Repository\BookRepositoryInterface;
use App\Book\UseCase\DTO\BookDTO;

class CreateBookUseCase
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository
    ) {}

    public function execute(BookDTO $data)
    {
        $book = $this->bookRepository->create($data);

        $this->bookRepository->attachAuthors($book->id, $data->authors);

        $this->bookRepository->attachSubjects($book->id, $data->subjects);

        return $book;
    }
}
