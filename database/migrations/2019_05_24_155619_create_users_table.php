<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('name');
			$table->string('email')->unique();
			$table->integer('number')->nullable()->unique();
			$table->integer('phoneNumber')->nullable()->unique('users_phonenumber_unique');
			$table->string('residence')->nullable();
			$table->string('zipCode')->nullable();
			$table->string('area')->nullable();
			$table->string('type')->nullable();
			$table->date('birthDate')->nullable();
			$table->string('photo')->nullable();
			$table->string('course')->nullable();
			$table->integer('curricularYear')->nullable();
			$table->integer('enruledYear')->nullable();
			$table->string('school')->nullable();
			$table->string('identificationDocument')->nullable();
			$table->integer('identificationNumber')->nullable();
			$table->bigInteger('nif')->nullable();
			$table->bigInteger('niss')->nullable();
			$table->bigInteger('sns')->nullable();
			$table->boolean('enee')->nullable();
			$table->text('educationalSupport', 65535)->nullable();
			$table->date('eneeExpirationDate')->nullable();
			$table->date('loginExpirationDate')->nullable();
			$table->string('password');
			$table->boolean('firstLogin')->nullable();
			$table->string('responsibleName')->nullable();
			$table->integer('responsiblePhone')->nullable();
			$table->string('responsibleEmail')->nullable();
			$table->string('responsibleKin')->nullable();
			$table->string('emergencyName')->nullable();
			$table->integer('emergencyPhone')->nullable();
			$table->string('emergencyEmail')->nullable();
			$table->string('emergencyKin')->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
