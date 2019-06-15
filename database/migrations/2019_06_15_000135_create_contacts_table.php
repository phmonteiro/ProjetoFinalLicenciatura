<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contacts', function(Blueprint $table)
		{
			$table->boolean('hasFiles')->nullable();
			$table->bigInteger('id', true)->unsigned();
			$table->string('studentEmail');
			$table->date('date');
			$table->string('service')->nullable();
			$table->string('decision');
			$table->text('information', 65535);
			$table->date('nextContact')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contacts');
	}

}
