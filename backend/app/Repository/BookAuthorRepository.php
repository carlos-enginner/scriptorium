<?php

namespace App\Repository;

use App\Model\BookAuthor;

class BookAuthorRepository
{
    public $timestamps = false;

    public function getAll()
    {
        return BookAuthor::all();
    }

    public function create(array $data)
    {
        return BookAuthor::create($data);
    }
}
