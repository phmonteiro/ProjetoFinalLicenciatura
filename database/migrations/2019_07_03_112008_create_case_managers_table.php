<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCaseManagersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('case_managers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('studentEmail')->unique('case_managers_studentemail_unique');
			$table->string('studentName');
			$table->string('caseManagerEmail')->unique('case_managers_casemanageremail_unique');
			$table->string('caseManagerName');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('case_managers');
	}

}
