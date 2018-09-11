<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStagesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('stages', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
			$table->integer('status_id')->nullable()->unsigned();
			$table->timestamps();

			$table->foreign('status_id')->references('id')->on('status');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::drop('stages');
	}
}
