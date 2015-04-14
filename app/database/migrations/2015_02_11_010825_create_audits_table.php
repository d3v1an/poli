<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuditsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audits', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('note_id',false);
			$table->boolean('audited')->default(false);
			$table->integer('user_id', false)->default(0);
			$table->integer('character_id', false);
			$table->enum('type', array('i','e'))->default('i');
			$table->timestamps();
			$table->index('note_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('audits');
	}

}
