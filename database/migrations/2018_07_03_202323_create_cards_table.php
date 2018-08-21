<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
			$table->string('number');
			$table->string('name');
			$table->string('date_due');
			$table->string('cvv');
			$table->string('brand');
			$table->string('hash');
            $table->integer('user_id')->nullable()->unsigned();
			$table->timestamps();
			$table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('cards');
    }
}
