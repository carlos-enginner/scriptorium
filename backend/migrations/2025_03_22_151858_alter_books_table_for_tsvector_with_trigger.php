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
use Hyperf\DbConnection\Db;

class AlterBooksTableForTsvectorWithTrigger extends Migration
{
    public function up()
    {
        Db::statement('ALTER TABLE books ADD COLUMN title_tsvector tsvector');

        Db::statement('CREATE INDEX idx_title_tsvector ON books USING gin(title_tsvector)');

        Db::statement('
            UPDATE books
            SET title_tsvector = to_tsvector(\'portuguese\', title)
        ');

        Db::statement('
            CREATE OR REPLACE FUNCTION update_title_tsvector()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.title_tsvector := to_tsvector(\'portuguese\', unaccent(NEW.title));
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        Db::statement('
            CREATE TRIGGER trg_update_title_tsvector
            BEFORE INSERT OR UPDATE ON books
            FOR EACH ROW
            EXECUTE FUNCTION update_title_tsvector();
        ');
    }

    public function down()
    {
        Db::statement('DROP TRIGGER IF EXISTS trg_update_title_tsvector ON books');
        Db::statement('DROP FUNCTION IF EXISTS update_title_tsvector');
        Db::statement('DROP INDEX IF EXISTS idx_title_tsvector');
        Db::statement('ALTER TABLE books DROP COLUMN IF EXISTS title_tsvector');
    }
}
