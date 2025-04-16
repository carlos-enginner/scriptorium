<?php

namespace App\Actor\Domain\Repository;

interface ActorRepositoryInterface
{
    public function getAll(): iterable;
    public function findByName(string $name): array;
}
