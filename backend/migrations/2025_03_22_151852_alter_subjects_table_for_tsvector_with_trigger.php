<?php

use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class AlterSubjectsTableForTsvectorWithTrigger extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE subjects ADD COLUMN description_tsvector tsvector');

        DB::statement('CREATE INDEX idx_description_tsvector ON subjects USING gin(description_tsvector)');

        DB::statement('
            UPDATE subjects
            SET description_tsvector = to_tsvector(\'portuguese\', description)
        ');

        DB::statement('
            CREATE OR REPLACE FUNCTION update_description_tsvector()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.description_tsvector := to_tsvector(\'portuguese\', unaccent(NEW.description));
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::statement('
            CREATE TRIGGER trg_update_description_tsvector
            BEFORE INSERT OR UPDATE ON subjects
            FOR EACH ROW
            EXECUTE FUNCTION update_description_tsvector();
        ');
    }

    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS trg_update_description_tsvector ON subjects');
        DB::statement('DROP FUNCTION IF EXISTS update_description_tsvector');
        DB::statement('DROP INDEX IF EXISTS idx_description_tsvector');
        DB::statement('ALTER TABLE subjects DROP COLUMN IF EXISTS description_tsvector');
    }
}
