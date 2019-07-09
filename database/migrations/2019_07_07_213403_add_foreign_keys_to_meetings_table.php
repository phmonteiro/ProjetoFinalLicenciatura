<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMeetingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('meetings', function(Blueprint $table)
		{
			$table->foreign('email', 'meetings_ibfk_1')->references('email')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('studentId', 'meetings_ibfk_2')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('meetings', function(Blueprint $table)
		{
			$table->dropForeign('meetings_ibfk_1');
			$table->dropForeign('meetings_ibfk_2');
		});
	}

}
