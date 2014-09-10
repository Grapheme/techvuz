<?php

class DatabaseSeeder extends Seeder {

	public function run(){
		Eloquent::unguard();
		
		$this->call('UserTableSeeder');
		$this->call('GroupsTableSeeder');
		$this->call('TablesSeeder');
		$this->call('ModulesTableSeeder');
	}

}