<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UserTableSeeder extends Seeder {

    public function run()
    {
		User::create([
			'name' => 'J',
			'email' => 'j@j.nl',
			'password' => bcrypt('Welkom01'),
			'superuser' => true,
		]);
    }

}