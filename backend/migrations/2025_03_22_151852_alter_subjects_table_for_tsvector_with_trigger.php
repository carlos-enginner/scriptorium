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

class AlterSubjectsTableForTsvectorWithTrigger extends Migration
{
    public function up()
    {
        Db::statement('ALTER TABLE subjects ADD COLUMN description_tsvector tsvector');

        Db::statement('CREATE INDEX idx_description_tsvector ON subjects USING gin(description_tsvector)');

        Db::statement('
            UPDATE subjects
            SET description_tsvector = to_tsvector(\'portuguese\', description)
        ');

        Db::statement('
            CREATE OR REPLACE FUNCTION update_description_tsvector()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.description_tsvector := to_tsvector(\'portuguese\', unaccent(NEW.description));
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        Db::statement('
            CREATE TRIGGER trg_update_description_tsvector
            BEFORE INSERT OR UPDATE ON subjects
            FOR EACH ROW
            EXECUTE FUNCTION update_description_tsvector();
        ');
    }

    public function down()
    {
        Db::statement('DROP TRIGGER IF EXISTS trg_update_description_tsvector ON subjects');
        Db::statement('DROP FUNCTION IF EXISTS update_description_tsvector');
        Db::statement('DROP INDEX IF EXISTS idx_description_tsvector');
        Db::statement('ALTER TABLE subjects DROP COLUMN IF EXISTS description_tsvector');
    }
}
