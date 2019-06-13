<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewForLatestFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_latest_fixes AS
            SELECT 
        `fixes`.`id` AS `id`,
        `fixes`.`ticket_id` AS `ticket_id`,
        MAX(`fixes`.`created_at`) AS `fix_date`,
        `fixes`.`fixed_by` AS `fixed_by`
    FROM
        `fixes`
    GROUP BY `fixes`.`ticket_id` , `fixes`.`fixed_by` , `fixes`.`id`
    ORDER BY `fixes`.`ticket_id` DESC;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW v_latest_fixes');
    }
}
