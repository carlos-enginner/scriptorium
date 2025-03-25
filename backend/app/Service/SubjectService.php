<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

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

    public function getSubjectsByDescription(string $subject)
    {
        return $this->repository->getSubjectsByDescription($subject);
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
