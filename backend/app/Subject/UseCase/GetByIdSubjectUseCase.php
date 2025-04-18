<?php

namespace App\Subject\UseCase;

use App\Subject\Domain\Repository\SubjectRepositoryInterface;

class GetByIdSubjectUseCase
{
    public function __construct(
        private readonly SubjectRepositoryInterface $subjectRepository
    ) {}

    public function execute(int $id)
    {
        return $this->subjectRepository->getById($id);
    }
}
