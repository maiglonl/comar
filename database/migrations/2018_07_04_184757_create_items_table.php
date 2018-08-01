<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateItemsTable.
 */
class CreateItemsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('items', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('order_id')->nullable()->unsigned();
			$table->integer('product_id')->nullable()->unsigned();
			$table->integer('amount')->nullable()->default(1);
			$table->float('value', 9, 2);
			$table->integer('interest_free')->nullable();
			$table->boolean('free_shipping')->nullable();
			$table->string('delivery_form')->nullable();
			$table->string('delivery_cost')->nullable();
			$table->integer('delivery_time')->nullable();
			$table->string('delivery_methods')->nullable();
			$table->integer('payment_installments')->nullable();
			$table->float('payment_installment',9,2)->nullable();
			$table->timestamps();

			$table->foreign('order_id')->references('id')->on('orders');
			$table->foreign('product_id')->references('id')->on('products');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::drop('items');
	}
}
