<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nees', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('studentEmail');
			$table->string('name');
			$table->integer('severity');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('nees');
	}

}
