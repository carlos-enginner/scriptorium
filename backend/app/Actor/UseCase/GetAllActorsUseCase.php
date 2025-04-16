<?php

namespace App\Actor\UseCase;

use App\Actor\Domain\Repository\ActorRepositoryInterface;

class GetAllActorsUseCase
{
    public function __construct(
        private ActorRepositoryInterface $actorRepository
    ) {}

    public function execute(?string $name = null)
    {
        return $this->actorRepository->getAll();
    }
}
