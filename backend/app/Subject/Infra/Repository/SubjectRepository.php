<?php

namespace App\Subject\Infra\Repository;

use App\Subject\Domain\Entity\Subject;
use App\Subject\Domain\Repository\SubjectRepositoryInterface;
use App\Subject\Infra\Model\SubjectModel;
use Carbon\Carbon;

class SubjectRepository implements SubjectRepositoryInterface
{
    public function getAll(): iterable
    {
        return SubjectModel::query()
            ->orderByDesc('id')
            ->get()
            ->map(fn(SubjectModel $model) => $this->toEntity($model))
            ->all();
    }

    private function toEntity(SubjectModel $model): Subject
    {
        return new Subject(
            id: $model->id,
            description: $model->description,
            createdAt: Carbon::parse($model->created_at),
            updatedAt: Carbon::parse($model->updated_at),
        );
    }

    // public function getSubjectsByDescription(string $subject)
    // {
    //     $subject = TsQuery::tokenizer($subject);

    //     return Subject::whereRaw('description_tsvector @@ to_tsquery(unaccent(?))', [$subject])->get();
    // }

    // public function findById(int $id)
    // {
    //     return Subject::find($id);
    // }

    public function create(array $data)
    {
        return SubjectModel::create($data);
    }

    public function update(int $id, array $data)
    {
        $subject = SubjectModel::find($id);
        if ($subject) {
            $subject->update($data);
        }
        return $subject;
    }

    public function delete(int $id)
    {
        return SubjectModel::destroy($id);
    }
}
