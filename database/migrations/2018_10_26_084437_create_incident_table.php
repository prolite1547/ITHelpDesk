<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->morphs('incident');
            $table->text('subject')->nullable();
            $table->text('details')->nullable();
            $table->unsignedTinyInteger('category')->nullable();
            $table->unsignedTinyInteger('catA')->nullable();
            $table->unsignedSmallInteger('catB')->nullable();
            $table->unsignedSmallInteger('catC')->nullable();
            $table->foreign('category')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('catA')->references('id')->on('category_a')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('catB')->references('id')->on('category_b')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('catC')->references('id')->on('category_c')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('incidents');
    }
}
