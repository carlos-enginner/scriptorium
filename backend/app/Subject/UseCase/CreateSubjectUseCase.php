<?php

namespace App\Subject\UseCase;

use App\Subject\Domain\Repository\SubjectRepositoryInterface;

class CreateSubjectUseCase
{
    public function __construct(
        private readonly SubjectRepositoryInterface $subjectRepository
    ) {}

    public function execute(array $data)
    {
        return $this->subjectRepository->create($data);
    }
}
