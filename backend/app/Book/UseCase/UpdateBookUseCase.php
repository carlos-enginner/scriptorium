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
        return $this->bookRepository->update($id, $data);
    }
}
