<?php

namespace App\Subject\UseCase;

use App\Subject\Domain\Repository\SubjectRepositoryInterface;

class UpdateSubjectUseCase
{
    public function __construct(
        private readonly SubjectRepositoryInterface $subjectRepository
    ) {}

    public function execute(int $id, array $data)
    {
        return $this->subjectRepository->update($id, $data);
    }
}
