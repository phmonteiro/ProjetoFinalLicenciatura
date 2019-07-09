<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCaseManagersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('case_managers', function(Blueprint $table)
		{
			$table->foreign('studentEmail', 'case_managers_ibfk_1')->references('email')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('caseManagerEmail', 'case_managers_ibfk_2')->references('email')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('case_managers', function(Blueprint $table)
		{
			$table->dropForeign('case_managers_ibfk_1');
			$table->dropForeign('case_managers_ibfk_2');
		});
	}

}
