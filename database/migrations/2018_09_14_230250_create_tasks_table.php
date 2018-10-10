<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('tasks', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->integer('stage_id')->unsigned();
			$table->integer('user_id')->nullable()->unsigned();
			$table->dateTime('date_conclusion')->nullable();
			$table->timestamps();

			$table->foreign('order_id')->references('id')->on('orders');
			$table->foreign('stage_id')->references('id')->on('stages');
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::drop('tasks');
	}
}
