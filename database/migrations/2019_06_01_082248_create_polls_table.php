<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('app_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('first_text')->nullable();
            $table->string('final_text')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::table('polls', function (Blueprint $table) {
            $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polls');
    }
}
