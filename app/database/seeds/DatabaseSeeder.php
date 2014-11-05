<?php

class DatabaseSeeder extends Seeder {

	public function run(){
		Eloquent::unguard();
		
		$this->call('UserTableSeeder');
		$this->call('GroupsTableSeeder');
		$this->call('ModulesTableSeeder');
		$this->call('UserActionsHistoryTableSeeder');
		$this->call('SystemMessagesTableSeeder');
		$this->call('AccountTypesTableSeeder');
		$this->call('PaymentStatusTableSeeder');
		$this->call('DirectionsCoursesTableSeeder');

        $this->call('TablesSeeder');
	}

}