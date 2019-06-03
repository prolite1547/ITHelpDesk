<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dev_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->text('project_name');
            $table->text('assigned_to');
            $table->text('status');
            $table->text('date_start')->nullable();
            $table->text('date_end')->nullable();
            $table->text('md50_status');
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
        Schema::dropIfExists('dev_projects');
    }
}
