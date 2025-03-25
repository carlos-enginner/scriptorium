<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Repository;

use App\Helper\TsQuery;
use App\Model\Author;

class AuthorRepository
{
    public function getAll()
    {
        return Author::all();
    }

    public function getAuthorByName(string $author)
    {
        $author = TsQuery::tokenizer($author);

        return Author::whereRaw('name_tsvector @@ to_tsquery(unaccent(?))', [$author])->get();
    }

    public function findById(int $id)
    {
        return Author::find($id);
    }

    public function create(array $data)
    {
        return Author::create($data);
    }

    public function update(int $id, array $data)
    {
        $author = Author::find($id);
        if ($author) {
            $author->update($data);
        }
        return $author;
    }

    public function delete(int $id)
    {
        return Author::destroy($id);
    }
}
