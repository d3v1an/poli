<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDevicedataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('devicedatas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('os', 15);
			$table->integer('os_version_major', false);
			$table->integer('os_version_minor', false);
			$table->integer('os_version_patch', false);
			$table->string('device', 15);
			$table->string('device_model', 15);
			$table->string('device_grade', 7);
			$table->string('browser', 15);
			$table->integer('browser_version_major', false);
			$table->integer('browser_version_minor', false);
			$table->integer('browser_version_patch', false);
			$table->boolean('is_desktop')->default(false);
			$table->boolean('is_mobile')->default(false);
			$table->boolean('is_tablet')->default(false);
			$table->boolean('is_robot')->default(false);
			$table->integer('css_version',false);
			$table->boolean('javascript_support');
			$table->text('user_agent');
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
		Schema::drop('devicedatas');
	}

}
