<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subjects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nome')->default('');
			$table->integer('hours')->default(0);
			$table->string('studentEmail');
			$table->string('semester', 2)->default('');
			$table->string('subjectCode', 20)->default('');
			$table->integer('yearLective');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subjects');
	}

}
