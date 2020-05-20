<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToServiceRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('service_requests', function(Blueprint $table)
		{
			$table->foreign('studentEmail', 'service_requests_ibfk_1')->references('email')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('service_requests', function(Blueprint $table)
		{
			$table->dropForeign('service_requests_ibfk_1');
		});
	}

}
