<?php

namespace App\Repository;

use App\Model\Subject;

class SubjectRepository
{
    public function getAll()
    {
        return Subject::all();
    }

    public function findById(int $id)
    {
        return Subject::find($id);
    }

    public function create(array $data)
    {
        return Subject::create($data);
    }

    public function update(int $id, array $data)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $subject->update($data);
        }
        return $subject;
    }

    public function delete(int $id)
    {
        return Subject::destroy($id);
    }
}
