<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('products', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->text('description')->nullable();
			$table->integer('category_id')->nullable()->unsigned();
			$table->foreign('category_id')->references('id')->on('categories');
			$table->float('value_partner', 9, 2)->nullable();
			$table->float('value_seller', 9, 2)->nullable();
			$table->integer('discount')->nullable()->default(0);
			$table->float('weight', 5, 2)->nullable(); //peso
			$table->integer('height')->nullable(); //altura
			$table->integer('width')->nullable(); //largura
			$table->integer('length')->nullable(); //comprimento
			$table->integer('diameter')->nullable(); //diametro
			$table->integer('quantity'); //quantidade
			$table->integer('interest_free')->nullable()->default(12);
			$table->boolean('free_shipping')->nullable()->default(false);
			$table->boolean('status')->nullable()->default(true);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::drop('products');
	}
}
