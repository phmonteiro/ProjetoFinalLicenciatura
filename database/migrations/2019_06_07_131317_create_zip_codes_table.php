<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateZipCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zip_codes', function(Blueprint $table)
		{
			$table->integer('cpo_id')->primary();
			$table->integer('cpo_cod4')->nullable();
			$table->integer('cpo_cod3')->nullable();
			$table->string('dsc_pos', 25)->nullable();
			$table->string('art_desig', 150)->nullable();
			$table->char('ide_num_pol', 1)->nullable();
			$table->integer('ide_num_pol_ini')->nullable();
			$table->integer('ide_num_pol_fim')->nullable();
			$table->string('ide_portas', 50)->nullable();
			$table->string('ide_desig', 100)->nullable();
			$table->string('dis_dsc', 50)->nullable();
			$table->string('con_dsc', 50)->nullable();
			$table->string('fre_dsc', 50)->nullable();
			$table->string('loc_dsc', 100)->nullable();
			$table->boolean('cpo_tem_rua')->nullable()->default(0);
			$table->index(['cpo_cod4','cpo_cod3','cpo_id'], 'zip_codes_IDX1');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('zip_codes');
	}

}
