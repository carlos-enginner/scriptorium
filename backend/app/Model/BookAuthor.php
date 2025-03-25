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

namespace App\Model;

/**
 * @property int $book_id
 * @property int $author_id
 */
class BookAuthor extends Model
{
    public bool $timestamps = false;

    public bool $incrementing = false;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'book_authors';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['book_id', 'author_id'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['book_id' => 'integer', 'author_id' => 'integer'];

    public function setKeysForSaveQuery($query)
    {
        return $query->where('book_id', $this->book_id)->where('author_id', $this->author_id);
    }
}
