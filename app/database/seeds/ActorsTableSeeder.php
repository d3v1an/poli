<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class ActorsTableSeeder extends Seeder {

	public function run()
	{
		Actor::create([
            'name'    => 'Claudia Artemisa Pavlovich Arellano',
            'rf_id'   => 212,
            'status'  => 1
        ]);

        Actor::create([
            'name'    => 'Javier Gándara Magaña',
            'rf_id'   => 926,
            'status'  => 1
        ]);
	}

}