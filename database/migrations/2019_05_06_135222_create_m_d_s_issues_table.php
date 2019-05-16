<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMDSIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_d_s_issues', function (Blueprint $table) {
            $table->increments('id');
            $table->text('issue_name');
            $table->text('status');
            $table->text('start_date');
            $table->text('end_date')->nullable();
            $table->integer('created_by');
            $table->softDeletes()->nullable();
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
        Schema::dropIfExists('m_d_s_issues');
    }
}
