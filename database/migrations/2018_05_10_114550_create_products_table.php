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
            $table->float('value', 9, 2)->nullable();
            $table->float('value', 9, 2)->nullable(); //peso
            $table->float('value', 9, 2)->nullable(); //altura
            $table->float('value', 9, 2)->nullable(); //largura
            $table->float('value', 9, 2)->nullable(); //comprimento
            $table->float('value', 9, 2)->nullable(); //diametro
            $table->float('value', 9, 2)->nullable(); //
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
