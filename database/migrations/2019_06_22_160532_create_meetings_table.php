<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeetingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meetings', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('service');
			$table->string('name');
			$table->integer('studentId');
			$table->string('email');
			$table->string('comment');
			$table->string('info')->nullable();
			$table->date('date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('meetings');
	}

}
