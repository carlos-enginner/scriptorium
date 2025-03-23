<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Subject extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'subjects';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['description'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
