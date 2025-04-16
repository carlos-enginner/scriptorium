<?php

namespace App\Subject\UseCase;

use App\Subject\Domain\Repository\SubjectRepositoryInterface;

class DeleteSubjectUseCase
{
    public function __construct(
        private readonly SubjectRepositoryInterface $subjectRepository
    ) {}

    public function execute(int $id)
    {
        return $this->subjectRepository->delete($id);
    }
}
