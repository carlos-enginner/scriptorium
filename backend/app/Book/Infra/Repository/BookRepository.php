<?php

namespace App\Book\Infra\Repository;

use App\Book\Domain\Entity\Book;
use App\Book\Domain\Repository\BookRepositoryInterface;
use App\Book\Infra\Model\BookModel;
use Carbon\Carbon;

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

    public function create(array $data)
    {
        return BookModel::create($data);
    }

    public function update(int $id, array $data)
    {
        $author = BookModel::find($id);
        if ($author) {
            $author->update($data);
        }
        return $author;
    }

    public function delete(int $id)
    {
        return BookModel::destroy($id);
    }
}
