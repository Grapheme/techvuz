<?php

class ModulesTableSeeder extends Seeder{

	public function run(){
		
		Module::create(array(
			'name' => 'system',
			'on' => 1,
			'order'=> 0
		));

		Module::create(array(
			'name' => 'pages',
			'on' => 1,
			'order'=> 0
		));

		Module::create(array(
			'name' => 'news',
			'on' => 1,
			'order'=> 0
		));

		Module::create(array(
			'name' => 'galleries',
			'on' => 1,
			'order'=> 0
		));

        /*
		Module::create(array(
			'name' => 'tags',
			'on' => 1,
			'order'=> 0
		));
        */

		Module::create(array(
			'name' => 'seo',
			'on' => 1,
			'order'=> 0
		));

		Module::create(array(
			'name' => 'dictionaries',
			'on' => 1,
			'order'=> 0
		));

	}

}