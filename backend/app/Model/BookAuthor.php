<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $book_id
 * @property int $author_id
 */
class BookAuthor extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'book_authors';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['book_id' => 'integer', 'author_id' => 'integer'];
}
