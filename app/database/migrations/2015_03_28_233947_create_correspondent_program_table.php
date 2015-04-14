<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCorrespondentProgramTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('correspondent_program', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('correspondent_id')->unsigned()->index();
			$table->foreign('correspondent_id')->references('id')->on('correspondents')->onDelete('cascade');
			$table->integer('program_id')->unsigned()->index();
			$table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('correspondent_program');
	}

}
