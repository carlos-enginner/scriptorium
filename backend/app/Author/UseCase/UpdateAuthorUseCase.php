<?php

declare(strict_types=1);

namespace App\Author\UseCase;

use App\Author\Domain\Repository\AuthorRepositoryInterface;

class UpdateAuthorUseCase
{
    public function __construct(
        private readonly AuthorRepositoryInterface $authorRepository
    ) {}

    public function execute(int $id, array $data)
    {
        return $this->authorRepository->update($id, $data);
    }
}
