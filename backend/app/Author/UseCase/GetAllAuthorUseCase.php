<?php

declare(strict_types=1);

namespace App\Author\UseCase;

use App\Author\Domain\Repository\AuthorRepositoryInterface;

class GetAllAuthorUseCase
{
    public function __construct(
        private readonly AuthorRepositoryInterface $authorRepository
    ) {}

    public function execute(?string $name = null)
    {
        return $this->authorRepository->getAll();
    }
}
