<?php

class TablesSeeder extends Seeder{

	public function run(){

		#DB::table('settings')->truncate();

		Setting::create(array('id' => 1,'name' => 'language','value' => 'ru'));
        Dictionary::create(array('slug'=>'reviews','name'=>'Отзывы','entity'=> 1,'icon_class'=>'fa-comments-o','hide_slug'=>1,'make_slug_from_name'=>1,'name_title'=>'Имя пользователя оставившего отзыв','pagination'=>30,'view_access'=>0,'sort_by'=>'created_at','sort_order_reverse'=>1,'sortable'=>1,'order'=>0));

        Dictionary::create(array('slug'=>'information-baners','name'=>'Информационные банеры','entity'=> 1,'icon_class'=>'fa-info','hide_slug'=>1,'make_slug_from_name'=>1,'name_title'=>'Название банера','pagination'=>30,'view_access'=>0,'sort_by'=>'created_at','sort_order_reverse'=>1,'sortable'=>1,'order'=>0));

        Dictionary::create(array('slug'=>'order-documents','name'=>'Документы','entity'=> 1,'icon_class'=>'fa-clipboard','hide_slug'=>1,'make_slug_from_name'=>1,'name_title'=>'Название документа','pagination'=>0,'view_access'=>2,'sort_by'=>'name','sort_order_reverse'=>0,'sortable'=>1,'order'=>0));
        $dic_id = Dictionary::where('slug','order-documents')->pluck('id');
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'order-documents-contract','name'=> 'Договор','order'=>1));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'order-documents-invoice','name'=> 'Счет','order'=>2));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'order-documents-act','name'=> 'Акт','order'=>3));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'order-documents-certificate-first','name'=> 'Сертификат первый ','order'=>4));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'order-documents-certificate-second','name'=> 'Сертификат второй ','order'=>5));

    }
}