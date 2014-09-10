<?php

class TablesSeeder extends Seeder{

	public function run(){

		#DB::table('settings')->truncate();

		Setting::create(array(
			'id' => 1,
			'name' => 'language',
			'value' => 'ru',
		));

	}
}