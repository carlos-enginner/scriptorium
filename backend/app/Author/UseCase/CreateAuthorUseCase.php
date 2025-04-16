<?php

declare(strict_types=1);

namespace App\Author\UseCase;

use App\Author\Domain\Repository\AuthorRepositoryInterface;

class CreateAuthorUseCase
{
    public function __construct(
        private readonly AuthorRepositoryInterface $authorRepository
    ) {}

    public function execute(array $data)
    {
        return $this->authorRepository->create($data);
    }
}
