<?php

use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class AlterAuthorsTableForTsvectorWithTrigger extends Migration
{
    public function up()
    {
       DB::statement('ALTER TABLE authors ADD COLUMN name_tsvector tsvector');

       DB::statement('CREATE INDEX idx_name_tsvector ON authors USING gin(name_tsvector)');

       DB::statement('
           UPDATE authors
           SET name_tsvector = to_tsvector(\'portuguese\', name)
       ');

       DB::statement('
           CREATE OR REPLACE FUNCTION update_name_tsvector() 
           RETURNS TRIGGER AS $$
           BEGIN
               NEW.name_tsvector := to_tsvector(\'portuguese\', NEW.name);
               RETURN NEW;
           END;
           $$ LANGUAGE plpgsql;
       ');

       DB::statement('
           CREATE TRIGGER trg_update_name_tsvector
           BEFORE INSERT OR UPDATE ON authors
           FOR EACH ROW
           EXECUTE FUNCTION update_name_tsvector();
       ');
    }

    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS trg_update_name_tsvector ON authors');
        DB::statement('DROP FUNCTION IF EXISTS update_name_tsvector');
        DB::statement('DROP INDEX IF EXISTS idx_name_tsvector');
        DB::statement('ALTER TABLE authors DROP COLUMN IF EXISTS name_tsvector');
    }
}
