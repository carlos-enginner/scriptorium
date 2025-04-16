<?php

namespace App\Book\Infra\Model;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $publisher
 * @property string $edition
 * @property string $publication_year
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class BookModel extends Model
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
        'publication_year',
        'price',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
