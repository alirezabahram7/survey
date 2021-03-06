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
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('dependant_option_id')->nullable();
            $table->string('text');
            $table->string('description')->nullable();
            $table->unsignedInteger('answer_type_id');
            $table->integer('position')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_required')->default(1);
            $table->timestamps();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
            $table->foreign('answer_type_id')->references('id')->on('answer_types')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
