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
        Schema::create('members_arc', function (Blueprint $table) {
            $table->id();
            $table->string('id_team');
            $table->string('full_name');
            $table->string('telephone');
            $table->string('lien_linked_in')->nullable();
            $table->string('lien_git_hub')->nullable();
            $table->string('photo_carte_identite');
            $table->string('email')->unique();
            $table->boolean('etudiant');
            $table->text('motivation');
            $table->text('skills');
            $table->text('proj');
            $table->string('fonction')->nullable();
            $table->string('password');
            $table->boolean('residence');
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
        Schema::dropIfExists('members_arcs');
    }
};
