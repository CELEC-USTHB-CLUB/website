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
        Schema::create('arc_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('wilaya');
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->boolean('is_student');
            $table->string('job')->nullable();
            $table->string('linkedIn_github')->nullable();
            $table->string('id_card_path');
            $table->boolean('need_hosting');
            $table->longText('skills');
            $table->longText('projects');
            $table->longText('motivation');
            $table->string('team_id');
            $table->string('password');
            $table->boolean('is_accepted')->default(false);
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
        Schema::dropIfExists('arc_registrations');
    }
};
