<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder {

	public function run()
	{
		User::create([
            'first_name'    => 'Ricardo',
            'last_name'     => 'Madrigal',
            'username'      => 'admin',
            'email'         => 'ricardom@gacomunicacion.com',
            'password'      =>  Hash::make('admin'),
            'role_id'		    => 1,
            'notifications' => 1
        ]);
	}

}