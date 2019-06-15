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
			$table->string('enee')->nullable();
			$table->text('educationalSupport', 65535)->nullable();
			$table->date('eneeExpirationDate')->nullable();
			$table->boolean('firstLogin')->nullable();
			$table->string('responsibleName')->nullable();
			$table->integer('responsiblePhone')->nullable();
			$table->string('responsibleEmail')->nullable();
			$table->string('responsibleKin')->nullable();
			$table->string('emergencyName')->nullable();
			$table->text('functionalAnalysis', 65535)->nullable();
			$table->integer('emergencyPhone')->nullable();
			$table->string('emergencyEmail')->nullable();
			$table->string('emergencyKin')->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->boolean('coordinatorApproval')->nullable();
			$table->string('servicesApproval', 15)->nullable();
			$table->timestamps();
			$table->string('secondEmail')->nullable();
			$table->integer('departmentNumber')->nullable();
			$table->string('gender', 50)->nullable();
			$table->date('dateEneeApproved')->nullable();
			$table->date('dateEneeRequested')->nullable();
			$table->string('serviceNameApproval')->nullable();
			$table->string('password')->nullable();
			$table->string('typeApplication')->nullable();
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
