<?php

namespace App\Service;

use App\Repository\SubjectRepository;
use Hyperf\Di\Annotation\Inject;

class SubjectService
{
    #[Inject]
    protected SubjectRepository $repository;

    public function getAllSubjects()
    {
        return $this->repository->getAll();
    }

    public function getSubjectById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function createSubject(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateSubject(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteSubject(int $id)
    {
        return $this->repository->delete($id);
    }
}
