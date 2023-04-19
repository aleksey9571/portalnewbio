<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkladNowsTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */

    
    public function up(){
        Schema::create('sklad_nows', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('categorie_id')->unsigned();
            $table->string('key');
            $table->string('name');
            $table->string('unit');
            $table->integer('quantity');
            $table->foreign('categorie_id')->references('id')->on('sklad_now_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('sklad_nows');
    }
}
