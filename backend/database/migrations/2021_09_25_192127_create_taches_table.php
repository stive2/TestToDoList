<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taches', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->string('titre');
            $table->text('description');
            $table->dateTime('date_fin');
            $table->dateTime('date_enreg');
            $table->enum('statut', ['En cours', 'Achevée', 'Faite'])->default('En cours');
            $table->unsignedInteger('user_id');
            $table->timestamps();

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
        Schema::dropIfExists('taches');
    }
}
