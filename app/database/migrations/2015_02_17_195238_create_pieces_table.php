<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePiecesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pieces', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('actor_id');
			$table->integer('topic_id');
			$table->integer('type_id');
			$table->enum('status',array('p','n','nn'));
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
		Schema::drop('pieces');
	}

}
