<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStudentSupportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('student_supports', function(Blueprint $table)
		{
			$table->foreign('email', 'student_supports_ibfk_1')->references('email')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('support_value', 'student_supports_ibfk_2')->references('value')->on('supports')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('student_supports', function(Blueprint $table)
		{
			$table->dropForeign('student_supports_ibfk_1');
			$table->dropForeign('student_supports_ibfk_2');
		});
	}

}
