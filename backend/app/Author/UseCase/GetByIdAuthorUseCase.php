<?php

declare(strict_types=1);

namespace App\Author\UseCase;

use App\Author\Domain\Repository\AuthorRepositoryInterface;

class GetByIdAuthorUseCase
{
    public function __construct(
        private readonly AuthorRepositoryInterface $authorRepository
    ) {}

    public function execute(int $id)
    {
        return $this->authorRepository->getById($id);
    }
}
