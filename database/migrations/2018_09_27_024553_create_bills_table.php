<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('bills', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('type'); //['debit', 'credit']
			$table->text('description')->nullable();
			$table->date('date_due')->nullable();
			$table->float('value')->nullable();
			$table->float('tax')->nullable();
			$table->boolean('done')->default(false);
			$table->integer('order_id')->unsigned();
			$table->integer('user_id')->nullable()->unsigned();
			$table->timestamps();

			$table->foreign('order_id')->references('id')->on('orders');
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::drop('bills');
	}
}
