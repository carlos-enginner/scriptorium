<?php

namespace App\Repository;

use App\Model\BookAuthor;
use Hyperf\DbConnection\Db;

class BookAuthorRepository
{
    public $timestamps = false;

    public function getAll()
    {
        return BookAuthor::all();
    }

    public function getAuthorsByBookId(int $bookId)
    {
        return BookAuthor::where('book_id', '=', $bookId)->pluck('author_id')->toArray();
    }

    public function upsert(int $bookId, array $subjects)
    {
        DB::table('book_authors')->where('book_id', $bookId)->delete();

        if (!empty($subjects)) {

            $values = array_map(fn($subjectId) => "($bookId, $subjectId)", $subjects);

            $sql = "INSERT INTO book_authors (book_id, author_id) VALUES "
                . implode(', ', $values)
                . " ON CONFLICT (book_id, author_id) DO NOTHING";

            return DB::statement($sql);
        }
    }

    public function create(array $data)
    {
        return BookAuthor::create($data);
    }
}
