<?php

class AccountTypesTableSeeder extends Seeder {

	public function run(){
        AccountTypes::create(array('title'=>'Расчетный'));
        AccountTypes::create(array('title'=>'Валютный'));
        AccountTypes::create(array('title'=>'Текущий'));
        AccountTypes::create(array('title'=>'Временный'));
        AccountTypes::create(array('title'=>'Транзитный'));
        AccountTypes::create(array('title'=>'Депозитный'));
	}
}