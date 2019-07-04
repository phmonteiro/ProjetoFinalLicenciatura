<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMedicalFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('medical_files', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('email');
			$table->string('fileName');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('medical_files');
	}

}
