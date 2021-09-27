<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etats', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->dateTime('date_enreg');
            $table->enum('statut', ['En cours', 'Achevée', 'Faite'])->default('En cours');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('tache_id');
            $table->timestamps();

            $table->foreign('tache_id')
                    ->references('id')
                    ->on('taches')
                    ->onDelete('cascade');

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etats');
    }
}
