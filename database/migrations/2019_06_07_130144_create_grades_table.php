<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGradesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('grades', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('email');
			$table->integer('year');
			$table->integer('semester');
			$table->string('name');
			$table->string('typeOfEvaluation');
			$table->integer('grade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('grades');
	}

}
