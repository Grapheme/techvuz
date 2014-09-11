<?php

class UserActionsHistoryTableSeeder extends Seeder{

	public function run(){
		
		#DB::table('dictionary')->truncate();
		#DB::table('dictionary_values')->truncate();
		Dictionary::create(array(
            'slug'=>'actions_types',
			'name'=>'Типы событий',
			'entity'=> NULL,
			'icon_class'=>'',
			'hide_slug'=>NULL,
			'name_title'=>NULL,
			'order'=>0
		));

        Dictionary::create(array(
            'slug'=>'actions_history',
            'name'=>'История событий',
            'entity'=> NULL,
            'icon_class'=>'',
            'hide_slug'=>NULL,
            'name_title'=>NULL,
            'order'=>1
        ));

        DicVal::create(array('dic_id'=>1,'slug'=>'directions.store','name'=> 'Добавлено направление','order'=>0));
        DicVal::create(array('dic_id'=>1,'slug'=>'directions.update','name'=> 'Обновлено направление','order'=>1));
        DicVal::create(array('dic_id'=>1,'slug'=>'directions.destroy','name'=> 'Удалено направление','order'=>2));

        DicVal::create(array('dic_id'=>1,'slug'=>'courses.store','name'=> 'Добавлен курс','order'=>3));
        DicVal::create(array('dic_id'=>1,'slug'=>'courses.update','name'=> 'Обновлен курс','order'=>4));
        DicVal::create(array('dic_id'=>1,'slug'=>'courses.destroy','name'=> 'Удален курс','order'=>5));

        DicVal::create(array('dic_id'=>1,'slug'=>'chapters.store','name'=> 'Добавлена глава','order'=>6));
        DicVal::create(array('dic_id'=>1,'slug'=>'chapters.update','name'=> 'Обновлена глава','order'=>7));
        DicVal::create(array('dic_id'=>1,'slug'=>'chapters.destroy','name'=> 'Удалена глава','order'=>8));

        DicVal::create(array('dic_id'=>1,'slug'=>'lectures.store','name'=> 'Добавлена лекция','order'=>9));
        DicVal::create(array('dic_id'=>1,'slug'=>'lectures.update','name'=> 'Обновлена лекция','order'=>10));
        DicVal::create(array('dic_id'=>1,'slug'=>'lectures.destroy','name'=> 'Удалена лекция','order'=>11));

        DicVal::create(array('dic_id'=>1,'slug'=>'testing.index','name'=> 'Добавлен тест','order'=>12));
        DicVal::create(array('dic_id'=>1,'slug'=>'testing.destroy','name'=> 'Удален тест','order'=>13));

	}

}