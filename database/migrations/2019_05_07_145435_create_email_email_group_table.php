<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailEmailGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_email_group', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('email_id');
            $table->unsignedInteger('email_group_id');
            $table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('email_group_id')->references('id')->on('email_groups')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('email_email_group');
    }
}
