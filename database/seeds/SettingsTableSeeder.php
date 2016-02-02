<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Settings;

class SettingsTableSeeder extends Seeder {

    public function run()
    {
		Settings::set('auth.registerenabled', true);
		Settings::set('auth.loginenabled', true);
    }

}