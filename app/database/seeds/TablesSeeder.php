<?php

class TablesSeeder extends Seeder{

	public function run(){

		#DB::table('settings')->truncate();

		Setting::create(array('id' => 1,'name' => 'language','value' => 'ru'));
        Dictionary::create(array('slug'=>'reviews','name'=>'Отзывы','entity'=> 1,'icon_class'=>'fa-comments-o','hide_slug'=>1,'make_slug_from_name'=>1,'name_title'=>'Имя пользователя оставившего отзыв','pagination'=>30,'view_access'=>0,'sort_by'=>'created_at','sort_order_reverse'=>1,'sortable'=>0,'order'=>0));
	}
}