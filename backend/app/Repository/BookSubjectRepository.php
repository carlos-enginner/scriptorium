<?php

namespace App\Repository;

use App\Model\BookSubject;
use Hyperf\DbConnection\Db;

class BookSubjectRepository
{
    public function getAll()
    {
        return BookSubject::all();
    }

    public function getSubjectsByBookId(int $bookId)
    {
        return BookSubject::where('book_id', '=', $bookId)->pluck('subject_id')->toArray();
    }

    public function upsert(int $bookId, array $subjects)
    {
        DB::table('book_subjects')->where('book_id', $bookId)->delete();

        if (!empty($subjects)) {

            $values = array_map(fn($subjectId) => "($bookId, $subjectId)", $subjects);

            $sql = "INSERT INTO book_subjects (book_id, subject_id) VALUES "
                . implode(', ', $values)
                . " ON CONFLICT (book_id, subject_id) DO NOTHING";

            return DB::statement($sql);
        }
    }

    public function create(array $data)
    {
        return BookSubject::create($data);
    }
}
