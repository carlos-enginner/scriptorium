<?php

namespace App\Book\UseCase;

use App\Book\Domain\Repository\BookRepositoryInterface;

class GetAllBookUseCase
{

    public function __construct(
        private readonly BookRepositoryInterface $bookRepository
    ) {}

    public function execute(?string $name = null)
    {
        return $this->bookRepository->getAll();
    }
}
