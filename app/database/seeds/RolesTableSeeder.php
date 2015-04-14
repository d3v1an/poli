<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class RolesTableSeeder extends Seeder {

	public function run()
	{
		Roles::create([
                'name'      => 'Administrador',
                'default'   => 0
        ]);

        Roles::create([
                'name'      => 'Usuario',
                'default'   => 1
        ]);
	}

}