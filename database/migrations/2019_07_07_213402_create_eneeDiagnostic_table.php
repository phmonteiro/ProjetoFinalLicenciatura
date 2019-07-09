<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEneeDiagnosticTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eneeDiagnostic', function(Blueprint $table)
		{
			$table->increments('id');
			$table->bigInteger('studentId')->nullable();
			$table->text('plan', 65535)->nullable();
			$table->text('diagnostic', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eneeDiagnostic');
	}

}
