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
        CONCAT_WS(' ', `u`.`fName`, `u`.`lName`) AS `fixed_by`
    FROM
        (`fixes`
        JOIN `users` `u` ON ((`fixes`.`fixed_by` = `u`.`id`)))
    GROUP BY `fixes`.`ticket_id`
    ORDER BY `fixes`.`ticket_id` DESC
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
