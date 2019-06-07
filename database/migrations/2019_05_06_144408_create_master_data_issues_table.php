<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterDataIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_data_issues', function (Blueprint $table) {
            $table->increments('id');
            $table->text('issue_name');
            $table->text('status');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->smallInteger('logged_by');
            $table->softDeletes();
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
        Schema::dropIfExists('master_data_issues');
    }
}
