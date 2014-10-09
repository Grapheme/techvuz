<?php


class PaymentStatusTableSeeder extends Seeder {

	public function run(){
        PaymentStatus::create(array('title'=>'Не оплачен','class'=>'non-paid-order'));
        PaymentStatus::create(array('title'=>'Оплачен','class'=>'paid-order'));
        PaymentStatus::create(array('title'=>'Частично оплачен','class'=>'part-order'));
        PaymentStatus::create(array('title'=>'Частично оплачен но доступ разрешен','class'=>'part-order'));
	}

}