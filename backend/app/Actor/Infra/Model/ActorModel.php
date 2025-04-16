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

namespace App\Actor\Infra\Model;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ActorModel extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'authors';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
