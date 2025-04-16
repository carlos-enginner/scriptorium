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
        return $this->bookRepository->create($data);
    }
}
