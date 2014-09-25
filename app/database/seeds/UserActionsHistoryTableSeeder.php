<?php

class UserActionsHistoryTableSeeder extends Seeder{

	public function run(){
		
		#DB::table('dictionary')->truncate();
		#DB::table('dictionary_values')->truncate();
        Dictionary::create(array('slug'=>'actions-types','name'=>'Типы событий','entity'=> 1,'icon_class'=>'fa-bolt','hide_slug'=>1,'make_slug_from_name'=>1,'name_title'=>'Название типа события','pagination'=>0,'view_access'=>1,'sort_by'=>NULL,'sort_order_reverse'=>0,'sortable'=>1,'order'=>0));
        Dictionary::create(array('slug'=>'actions-history','name'=>'История событий','entity'=> 1,'icon_class'=>'fa-bell','hide_slug'=>1,'make_slug_from_name'=>1,'name_title'=>'','pagination'=>30,'view_access'=>1,'sort_by'=>'created_at','sort_order_reverse'=>1,'sortable'=>0,'order'=>0));

        $dic_id = Dictionary::where('slug','actions-types')->first()->id;

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'directions.store','name'=> 'Добавлено направление','order'=>0));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'directions.update','name'=> 'Обновлено направление','order'=>1));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'directions.destroy','name'=> 'Удалено направление','order'=>2));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'courses.store','name'=> 'Добавлен курс','order'=>3));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'courses.update','name'=> 'Обновлен курс','order'=>4));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'courses.destroy','name'=> 'Удален курс','order'=>5));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'chapters.store','name'=> 'Добавлена глава','order'=>6));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'chapters.update','name'=> 'Обновлена глава','order'=>7));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'chapters.destroy','name'=> 'Удалена глава','order'=>8));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'lectures.store','name'=> 'Добавлена лекция','order'=>9));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'lectures.update','name'=> 'Обновлена лекция','order'=>10));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'lectures.destroy','name'=> 'Удалена лекция','order'=>11));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'testing.index','name'=> 'Добавлен тест','order'=>12));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'testing.destroy','name'=> 'Удален тест','order'=>13));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'dobavlen-otzuv','name'=> 'Добавлен отзыв','order'=>14));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'otredaktirovan-otzuv','name'=> 'Обновлен отзыв','order'=>15));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'udalen-otzuv','name'=> 'Удален отзыв','order'=>16));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'dobavlena-novost','name'=> 'Добавлена новость','order'=>17));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'otredaktirovana-novost','name'=> 'Обновлена новость','order'=>18));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'udalena-novost','name'=> 'Удалена новость','order'=>19));

	}

}