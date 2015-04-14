<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeodatasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geodatas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('city');
			$table->string('country');
			$table->string('country_code', 3);
			$table->string('latitude', 45);
			$table->string('longitude', 45);
			$table->string('region');
			$table->string('region_name');
			$table->string('time_zone', 120);
			$table->string('zip', 16);
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
		Schema::drop('geodatas');
	}

}
