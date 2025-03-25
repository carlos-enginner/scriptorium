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
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $query = 'ALTER TABLE public.subjects '
            . 'ALTER COLUMN description TYPE varchar(40) '
            . 'USING description::varchar(40);';

        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $query = 'ALTER TABLE public.subjects '
            . 'ALTER COLUMN description TYPE varchar(20) '
            . 'USING description::varchar(20);';

        DB::statement($query);
    }
};
