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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string("fullname");
            $table->string("email")->unique();
            $table->string("birthdate");
            $table->string("registration_number");
            $table->boolean("is_usthb_student");
            $table->string("study_level");
            $table->string("study_field");
            $table->longText("projects")->nullable();
            $table->longText("intersted_in")->nullable();
            $table->json("skills")->nullable();
            $table->longText("other_clubs_experience")->nullable();
            $table->string("linked_in")->nullable();
            $table->longText("motivation");
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
        Schema::dropIfExists('members');
    }
};
