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

use App\Model\BookSubject;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class BookSubjectRepository
{
    #[Inject]
    protected SubjectRepository $subjectRepository;

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
        // remove se não existir na tabela api
        $items = [];
        foreach ($subjects as $subjectId) {
            if ($this->subjectRepository->findById($subjectId)) {
                $items[] = $subjectId;
            }
        }

        Db::table('book_subjects')->where('book_id', $bookId)->delete();

        if (! empty($items)) {
            $values = array_map(fn ($subjectId) => "({$bookId}, {$subjectId})", $items);

            $sql = 'INSERT INTO book_subjects (book_id, subject_id) VALUES '
                . implode(', ', $values)
                . ' ON CONFLICT (book_id, subject_id) DO NOTHING';

            return Db::statement($sql);
        }
    }

    public function create(array $data)
    {
        return BookSubject::create($data);
    }
}
