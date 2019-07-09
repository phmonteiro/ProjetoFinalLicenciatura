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
			$table->string('studentEmail');
			$table->string('studentName');
			$table->string('caseManagerEmail')->index('caseManagerEmail');
			$table->string('caseManagerName');
			$table->unique(['studentEmail','caseManagerEmail'], 'uq_studentEmail_caseManagerEmail');
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
