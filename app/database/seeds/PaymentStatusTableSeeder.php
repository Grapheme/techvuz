<?php


class PaymentStatusTableSeeder extends Seeder {

	public function run(){
        PaymentStatus::create(array('title'=>'Не оплачен'));
        PaymentStatus::create(array('title'=>'Оплачен'));
        PaymentStatus::create(array('title'=>'Частично оплачен'));
	}

}