<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contacts', function (Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->string('studentEmail');
			$table->date('date');
			$table->string('service')->nullable();
			$table->string('decision');
			$table->text('information', 65535);
			$table->date('nextContact');
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
