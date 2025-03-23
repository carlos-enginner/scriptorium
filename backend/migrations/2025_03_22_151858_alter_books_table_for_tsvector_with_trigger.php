<?php

use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class AlterBooksTableForTsvectorWithTrigger extends Migration
{
    public function up()
    {
        Db::statement('ALTER TABLE books ADD COLUMN title_tsvector tsvector');

        DB::statement('CREATE INDEX idx_title_tsvector ON books USING gin(title_tsvector)');

        DB::statement('
            UPDATE books
            SET title_tsvector = to_tsvector(\'portuguese\', title)
        ');

        DB::statement('
            CREATE OR REPLACE FUNCTION update_title_tsvector()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.title_tsvector := to_tsvector(\'portuguese\', unaccent(NEW.title));
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::statement('
            CREATE TRIGGER trg_update_title_tsvector
            BEFORE INSERT OR UPDATE ON books
            FOR EACH ROW
            EXECUTE FUNCTION update_title_tsvector();
        ');
    }

    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS trg_update_title_tsvector ON books');
        DB::statement('DROP FUNCTION IF EXISTS update_title_tsvector');
        DB::statement('DROP INDEX IF EXISTS idx_title_tsvector');
        DB::statement('ALTER TABLE books DROP COLUMN IF EXISTS title_tsvector');
    }
}
