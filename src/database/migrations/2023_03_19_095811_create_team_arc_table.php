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
        Schema::create('team_arc', function (Blueprint $table) {
            $table->id();
            $table->integer('tid');
            $table->string('nom_team');
            $table->string('region_team');
            $table->string('nbr_team')->nullable();
            $table->boolean('accepted_team')->nullable();
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
        Schema::dropIfExists('team_arc');
    }
};
