<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->nullable()->unsigned();
			$table->integer('status_id')->nullable()->unsigned()->default(1);
			$table->string('zipcode', 9)->nullable();
			$table->string('state', 25)->nullable();
			$table->string('city', 100)->nullable();
			$table->string('district', 100)->nullable();
			$table->string('street', 150)->nullable();
			$table->integer('number')->nullable();
			$table->string('complement', 150)->nullable();
			$table->string('payment_method')->nullable();
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('status_id')->references('id')->on('status');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::drop('orders');
	}
}
