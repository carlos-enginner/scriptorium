<?php

namespace App\Book\Infra\Repository;

use App\Book\Domain\Entity\Book;
use App\Book\Domain\Repository\BookRepositoryInterface;
use App\Book\Infra\Model\BookModel;
use App\Book\UseCase\DTO\BookDTO;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;

class BookRepository implements BookRepositoryInterface
{
    public function getAll(): iterable
    {
        return BookModel::query()
            ->orderByDesc('id')
            ->get()
            ->map(fn(BookModel $model) => $this->toEntity($model))
            ->all();
    }

    private function toEntity(BookModel $model): Book
    {
        return new Book(
            id: $model->id,
            title: $model->title,
            publisher: $model->publisher,
            edition: $model->edition,
            publicationYear: (int) $model->publication_year,
            price: (float) $model->price,
            createdAt: $model->created_at ? Carbon::parse($model->created_at) : null,
            updatedAt: $model->updated_at ? Carbon::parse($model->updated_at) : null,
        );
    }

    public function create(BookDTO $dto)
    {
        return BookModel::create($dto->toArray());
    }

    public function update(int $id, BookDTO $dto)
    {
        $author = BookModel::find($id);
        if ($author) {
            $author->update($dto->toArray());
        }
        return $author;
    }

    public function delete(int $id)
    {
        return BookModel::destroy($id);
    }

    public function attachAuthors(int $bookId, array $authorIds = [])
    {
        Db::table('book_authors')->where('book_id', $bookId)->delete();

        $data = array_map(fn($authorId) => ['book_id' => $bookId, 'author_id' => $authorId], $authorIds);

        Db::table('book_authors')->insert($data);
    }

    public function attachSubjects(int $bookId, array $subjectsIds = [])
    {
        Db::table('book_subjects')->where('book_id', $bookId)->delete();

        $data = array_map(fn($subjectId) => ['book_id' => $bookId, 'subject_id' => $subjectId], $subjectsIds);

        Db::table('book_subjects')->insert($data);
    }
}
