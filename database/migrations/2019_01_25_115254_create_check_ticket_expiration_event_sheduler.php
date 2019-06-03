<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckTicketExpirationEventSheduler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
           SET GLOBAL event_scheduler = ON;
           
            CREATE EVENT update_ticket_status_exp
            ON SCHEDULE EVERY 1 MINUTE
            STARTS CURRENT_TIMESTAMP
            DO
                UPDATE tickets set status = 6 WHERE now() >= expiration AND status NOT IN (3,4);
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
