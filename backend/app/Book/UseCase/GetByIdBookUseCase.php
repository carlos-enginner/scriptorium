<?php

namespace App\Book\UseCase;

use App\Book\Domain\Repository\BookRepositoryInterface;

class GetByIdBookUseCase
{

    public function __construct(
        private readonly BookRepositoryInterface $bookRepository
    ) {}

    public function execute(int $id)
    {
        return $this->bookRepository->getById($id);
    }
}
