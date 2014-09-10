<?php

class UserTableSeeder extends Seeder{

	public function run(){
		
		#DB::table('users')->truncate();

		User::create(array(
            'group_id'=>1,
			'name'=>'Администратор',
			'surname'=>'',
			'email'=>'admin@techvuz.ru',
			'active'=>1,
			'password'=>Hash::make('grapheme1234'),
			'photo'=>'',
			'thumbnail'=>'',
			'temporary_code'=>'',
			'code_life'=>0,
		));

		User::create(array(
            'group_id'=>2,
			'name'=>'Пользователь',
			'surname'=>'',
			'email'=>'user@techvuz.ru',
			'active'=>1,
			'password'=>Hash::make('000000'),
			'photo'=>'',
			'thumbnail'=>'',
			'temporary_code'=>'',
			'code_life'=>0,
		));

		User::create(array(
            'group_id'=>3,
			'name'=>'Модератор',
			'surname'=>'',
			'email'=>'moder@techvuz.ru',
			'active'=>1,
			'password'=>Hash::make('111111'),
			'photo'=>'',
			'thumbnail'=>'',
			'temporary_code'=>'',
			'code_life'=>0,
		));
	}

}