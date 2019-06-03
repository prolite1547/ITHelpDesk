<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreVisitDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_visit_details', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumInteger('store_id');
            $table->smallInteger('it_personnel');
            $table->tinyInteger('status_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->smallInteger('logged_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_visit_details');
    }
}
