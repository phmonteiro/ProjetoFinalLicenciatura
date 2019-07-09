<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('nees', function(Blueprint $table)
		{
			$table->foreign('studentEmail', 'nees_ibfk_1')->references('email')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('nees', function(Blueprint $table)
		{
			$table->dropForeign('nees_ibfk_1');
		});
	}

}
