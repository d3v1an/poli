<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSauronsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('saurons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->binary('ip');
			$table->string('hostname',  200);
			$table->string('network');
			$table->string('isp',  200);
			$table->string('lang', 2);
			$table->text('referer')->nullable();
			$table->integer('geodata_id',false);
			$table->integer('devicedata_id',false);
			$table->integer('segment_id',false);
			$table->boolean('is_user')->default(false);
			$table->integer('user_id')->default(false);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('saurons');
	}

}
