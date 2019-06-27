<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentSupportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_supports', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email');
			$table->integer('support_value')->unsigned()->index('support_value');
			$table->unique(['email','support_value'], 'email_support');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('student_supports');
	}

}
