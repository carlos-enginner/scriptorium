<?php

namespace App\Repository;

use App\Model\Book;

class BookRepository
{
    public function getAll()
    {
        return Book::all();
    }

    public function getBooksByTitle(string $title)
    {
        return Book::whereRaw('title_tsvector @@ to_tsquery(unaccent(?))', [$title])->get();
    }

    public function findById(int $id)
    {
        return Book::find($id);
    }

    public function create(array $data)
    {
        return Book::create($data);
    }

    public function update(int $id, array $data)
    {
        $book = Book::find($id);
        if ($book) {
            $book->update($data);
        }
        return $book;
    }

    public function delete(int $id)
    {
        return Book::destroy($id);
    }
}
