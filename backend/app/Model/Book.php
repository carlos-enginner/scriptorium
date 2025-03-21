<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $publisher
 * @property string $edition
 * @property string $publication_year
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Book extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'books';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'title',
        'publisher',
        'edition',
        'publication_year'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
