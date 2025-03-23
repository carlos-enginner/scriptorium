<?php

namespace App\Repository;

use App\Model\BookAuthor;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class BookAuthorRepository
{
    #[Inject]
    protected BookRepository $bookRepository;

    public function getAll()
    {
        return BookAuthor::all();
    }

    public function getAuthorsByBookId(int $bookId)
    {
        return BookAuthor::where('book_id', '=', $bookId)->pluck('author_id')->toArray();
    }

    public function upsert(int $bookId, array $authors)
    {
        // remove se não existir na tabela api
        $items = [];
        foreach ($authors as $authorId) {
            if ($this->bookRepository->findById($authorId) === null) {
                $items[] = $authorId;
            }
        }

        DB::table('book_authors')->where('book_id', $bookId)->delete();

        if (!empty($items)) {

            $values = array_map(fn($authorId) => "($bookId, $authorId)", $items);

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
