<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder {

	public function run()
	{
		User::create([
            'first_name'    => 'Admin',
            'last_name'     => 'Admin',
            'username'      => 'admin',
            'password'      =>  Hash::make('admin'),
            'role_id'		    => 1
        ]);
	}

}