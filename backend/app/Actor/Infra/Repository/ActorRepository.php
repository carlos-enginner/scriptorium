<?php

namespace App\Actor\Infra\Repository;

use App\Actor\Domain\Entity\Actor;
use App\Actor\Domain\Repository\ActorRepositoryInterface;
use App\Actor\Infra\Model\ActorModel;
use Carbon\Carbon;

class ActorRepository implements ActorRepositoryInterface
{
    public function getAll(): iterable
    {
        return ActorModel::query()
            ->orderByDesc('id')
            ->get()
            ->map(fn(ActorModel $model) => $this->toEntity($model))
            ->all();
    }

    public function findByName(string $name): array
    {
        return ["infra_find_by_name"];
    }

    private function toEntity(ActorModel $model): Actor
    {
        return new Actor(
            id: $model->id,
            name: $model->name,
            createdAt: Carbon::parse($model->created_at),
            updatedAt: Carbon::parse($model->updated_at),
        );
    }
}
