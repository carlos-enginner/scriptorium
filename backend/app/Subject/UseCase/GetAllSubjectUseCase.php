<?php

namespace App\Subject\UseCase;

use App\Subject\Domain\Repository\SubjectRepositoryInterface;

class GetAllSubjectUseCase
{
    public function __construct(
        private readonly SubjectRepositoryInterface $subjectRepository
    ) {}

    public function execute(?string $name = null)
    {
        return $this->subjectRepository->getAll();
    }
}
