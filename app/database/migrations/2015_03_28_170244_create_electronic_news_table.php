<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateElectronicNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('electronic_news', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('date');
			$table->time('hour');
			$table->string('title');
			$table->string('header');
			$table->text('note');
			$table->string('type');
			$table->string('file');
			$table->integer('program_id',false);
			$table->integer('comunicator_id',false);
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
		Schema::drop('electronic_news');
	}

}
