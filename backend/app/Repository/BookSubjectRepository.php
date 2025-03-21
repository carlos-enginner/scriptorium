<?php

namespace App\Repository;

use App\Model\BookSubject;

class BookSubjectRepository
{
    public function getAll()
    {
        return BookSubject::all();
    }

    public function create(array $data)
    {
        return BookSubject::create($data);
    }
}
