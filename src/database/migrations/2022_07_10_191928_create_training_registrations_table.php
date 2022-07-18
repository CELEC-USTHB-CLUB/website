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
        Schema::create('training_registrations', function (Blueprint $table) {
            $table->id();
            $table->integer('training_id');
            $table->string('fullname');
            $table->string('email');
            $table->string('registration_number');
            $table->string('phone');
            $table->boolean('is_celec_memeber');
            $table->string('study_level');
            $table->string('study_field');
            $table->longText('course_goals');
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
        Schema::dropIfExists('training_registrations');
    }
};
