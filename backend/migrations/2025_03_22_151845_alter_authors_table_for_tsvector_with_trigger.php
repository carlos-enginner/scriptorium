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

class AlterAuthorsTableForTsvectorWithTrigger extends Migration
{
    public function up()
    {
        Db::statement('CREATE EXTENSION IF NOT EXISTS "unaccent"');

        Db::statement('ALTER TABLE authors ADD COLUMN name_tsvector tsvector');

        Db::statement('CREATE INDEX idx_name_tsvector ON authors USING gin(name_tsvector)');

        Db::statement('
           UPDATE authors
           SET name_tsvector = to_tsvector(\'portuguese\', name)
       ');

        Db::statement('
           CREATE OR REPLACE FUNCTION update_name_tsvector()
           RETURNS TRIGGER AS $$
           BEGIN
               NEW.name_tsvector := to_tsvector(\'portuguese\', unaccent(NEW.name));
               RETURN NEW;
           END;
           $$ LANGUAGE plpgsql;
       ');

        Db::statement('
           CREATE TRIGGER trg_update_name_tsvector
           BEFORE INSERT OR UPDATE ON authors
           FOR EACH ROW
           EXECUTE FUNCTION update_name_tsvector();
       ');
    }

    public function down()
    {
        Db::statement('DROP TRIGGER IF EXISTS trg_update_name_tsvector ON authors');
        Db::statement('DROP FUNCTION IF EXISTS update_name_tsvector');
        Db::statement('DROP INDEX IF EXISTS idx_name_tsvector');
        Db::statement('ALTER TABLE authors DROP COLUMN IF EXISTS name_tsvector');
    }
}
