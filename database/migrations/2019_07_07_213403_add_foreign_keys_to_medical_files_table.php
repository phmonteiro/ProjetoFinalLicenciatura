<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMedicalFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('medical_files', function(Blueprint $table)
		{
			$table->foreign('email', 'medical_files_ibfk_1')->references('email')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('medical_files', function(Blueprint $table)
		{
			$table->dropForeign('medical_files_ibfk_1');
		});
	}

}
