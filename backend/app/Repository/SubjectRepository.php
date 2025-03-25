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
use App\Model\Subject;

class SubjectRepository
{
    public function getAll()
    {
        return Subject::all();
    }

    public function getSubjectsByDescription(string $subject)
    {
        $subject = TsQuery::tokenizer($subject);

        return Subject::whereRaw('description_tsvector @@ to_tsquery(unaccent(?))', [$subject])->get();
    }

    public function findById(int $id)
    {
        return Subject::find($id);
    }

    public function create(array $data)
    {
        return Subject::create($data);
    }

    public function update(int $id, array $data)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $subject->update($data);
        }
        return $subject;
    }

    public function delete(int $id)
    {
        return Subject::destroy($id);
    }
}
