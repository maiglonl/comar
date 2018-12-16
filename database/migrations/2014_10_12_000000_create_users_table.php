<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
			$table->string('username', 20)->unique();
			$table->string('password', 100);
			$table->string('email', 100)->unique();
			$table->string('cp', 18)->unique();
			$table->string('phone1', 11);
			$table->string('phone2', 11)->nullable();
			$table->string('gender', 6);
			$table->date('birthdate')->nullable();
			$table->string('zipcode', 9);
			$table->string('state', 25);
			$table->string('city', 100);
			$table->string('district', 100);
			$table->string('street', 150);
			$table->integer('number');
			$table->string('complement', 150)->nullable();
			$table->integer('status')->nullable();
			$table->string('role')->notNull()->default(USER_ROLES_PARTNER);
			$table->integer('parent_id')->nullable()->unsigned();
			$table->foreign('parent_id')->references('id')->on('users');
			$table->integer('position')->nullable();
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('users');
	}
}
