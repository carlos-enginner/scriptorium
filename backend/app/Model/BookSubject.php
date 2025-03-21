<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $book_id
 * @property int $subject_id
 */
class BookSubject extends Model
{
    public bool $timestamps = false;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'book_subjects';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['book_id', 'subject_id'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['book_id' => 'integer', 'subject_id' => 'integer'];

    public bool $incrementing = false;

    public function setKeysForSaveQuery($query)
    {
        return $query->where('book_id', $this->book_id)->where('subject_id', $this->author_id);
    }
}
