<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('poll_id');
            $table->string('text');
            $table->unsignedInteger('answer_type_id');
            $table->integer('position')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_required')->default(1);
            $table->timestamps();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
            $table->foreign('answer_type_id')->references('id')->on('answer_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
