<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepositoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repositorios', function (Blueprint $table) {
            $table->increments('REP_ID');

            $table->string('REP_LANG', 100);

            $table->integer('REP_ORDER')->unsigned();

            $table->string('REP_NAME', 100);
            $table->string('REP_AUTHOR', 100);
            $table->string('REP_URL', 200);
            $table->longText('REP_DESC');
            $table->string('REP_STARS', 6);
            $table->string('REP_FORKS', 6);
            $table->string('REP_BUILTBY', 3000);
            $table->longText('REP_TREE');
            $table->string('REP_SHA');

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
        Schema::dropIfExists('repositorios');
    }
}
