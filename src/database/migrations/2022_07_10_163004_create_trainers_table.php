<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->string("fullname");
            $table->string("email");
            $table->boolean("is_usthb_student");
            $table->string("study_level");
            $table->string("study_field");
            $table->longText("projects")->nullable();
            $table->string("phone");
            $table->string("course_title");
            $table->longText("course_description");
            $table->string("linked_in")->nullable();
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
        Schema::dropIfExists('trainers');
    }
};
