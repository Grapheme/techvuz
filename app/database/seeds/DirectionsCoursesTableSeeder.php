<?php

class DirectionsCoursesTableSeeder extends Seeder{

	public function run(){
		
		DB::table('directions')->truncate();
		DB::table('courses')->truncate();

        Directions::create(array('order'=>1,'code'=>'С','title'=>'Строительство','photo_id'=>NULL,'description'=>''));
        Directions::create(array('order'=>2,'code'=>'П','title'=>'Проектирование','photo_id'=>NULL,'description'=>''));
        Directions::create(array('order'=>3,'code'=>'ИЗ','title'=>'Инженерные изыскания','photo_id'=>NULL,'description'=>''));
        Directions::create(array('order'=>4,'code'=>'ПБ','title'=>'Пожарная безопасность','photo_id'=>NULL,'description'=>''));
        Directions::create(array('order'=>5,'code'=>'ИО','title'=>'Инженерное обеспечение','photo_id'=>NULL,'description'=>''));
        Directions::create(array('order'=>6,'code'=>'КГЗ','title'=>'Конструкции гражданских зданий','photo_id'=>NULL,'description'=>''));

    }

}