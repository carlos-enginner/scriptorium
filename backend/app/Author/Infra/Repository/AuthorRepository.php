<?php

namespace App\Author\Infra\Repository;

use App\Author\Domain\Entity\Author;
use App\Author\Domain\Repository\AuthorRepositoryInterface;
use App\Author\Infra\Model\AuthorModel;
use App\Helper\TsQuery;
use Carbon\Carbon;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function getAll(): iterable
    {
        return AuthorModel::query()
            ->orderByDesc('id')
            ->get()
            ->map(fn(AuthorModel $model) => $this->toEntity($model))
            ->all();
    }

    public function getById(int $id): ?Author
    {
        $model = AuthorModel::query()
            ->where('id', $id)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    private function toEntity(AuthorModel $model): Author
    {
        return new Author(
            id: $model->id,
            name: $model->name,
            createdAt: Carbon::parse($model->created_at),
            updatedAt: Carbon::parse($model->updated_at),
        );
    }

    public function getAuthorByName(string $author)
    {
        $author = TsQuery::tokenizer($author);

        return AuthorModel::whereRaw('name_tsvector @@ to_tsquery(unaccent(?))', [$author])->get();
    }

    public function findById(int $id)
    {
        return AuthorModel::find($id);
    }

    public function create(array $data)
    {
        return AuthorModel::create($data);
    }

    public function update(int $id, array $data)
    {
        $author = AuthorModel::find($id);
        if ($author) {
            $author->update($data);
        }
        return $author;
    }

    public function delete(int $id)
    {
        return AuthorModel::destroy($id);
    }
}
