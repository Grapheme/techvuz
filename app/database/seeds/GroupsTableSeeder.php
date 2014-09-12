<?php

class GroupsTableSeeder extends Seeder{

	public function run(){
		
		#DB::table('groups')->truncate();
		Group::create(array(
			'name' => 'admin',
			'desc' => 'Администраторы',
			'dashboard' => 'admin'
		));
		Group::create(array(
			'name' => 'user',
			'desc' => 'Пользователи',
			'dashboard' => ''
		));
		Group::create(array(
			'name' => 'moderator',
			'desc' => 'Модераторы',
			'dashboard' => 'admin'
		));
        Group::create(array(
            'name' => 'organization',
            'desc' => 'Юридическое лицо',
            'dashboard' => 'organization'
        ));
        Group::create(array(
            'name' => 'listener',
            'desc' => 'Сотрудник организации',
            'dashboard' => 'listener'
        ));
        Group::create(array(
            'name' => 'individual',
            'desc' => 'Индивидуальный слушатель',
            'dashboard' => 'individual-listener'
        ));
	}
}