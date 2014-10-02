<?php

class DirectionsCoursesTableSeeder extends Seeder{

	public function run(){
		
		DB::table('directions')->truncate();
		DB::table('courses')->truncate();

        Directions::create(array('order'=>1,'code'=>'С','title'=>'Строительство','photo_id'=>NULL,'description'=>''));
        $direction_id = Directions::where('order',1)->first()->id;
        Courses::create(array('direction_id'=>$direction_id,'order'=>1,'code'=>'C-1','title'=>'Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>2,'code'=>'C-2','title'=>'Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>3,'code'=>'C-3','title'=>'Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах ','description'=>'','price'=>3000,'hours'=>72));

        Directions::create(array('order'=>2,'code'=>'П','title'=>'Проектирование','photo_id'=>NULL,'description'=>''));
        $direction_id = Directions::where('order',2)->first()->id;
        Courses::create(array('direction_id'=>$direction_id,'order'=>1,'code'=>'П-1','title'=>'Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>2,'code'=>'П-2','title'=>'Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>3,'code'=>'П-3','title'=>'Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах ','description'=>'','price'=>3000,'hours'=>72));

        Directions::create(array('order'=>3,'code'=>'ИЗ','title'=>'Инженерные изыскания','photo_id'=>NULL,'description'=>''));
        $direction_id = Directions::where('order',3)->first()->id;
        Courses::create(array('direction_id'=>$direction_id,'order'=>1,'code'=>'ИЗ-1','title'=>'Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>2,'code'=>'ИЗ-2','title'=>'Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>3,'code'=>'ИЗ-3','title'=>'Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах ','description'=>'','price'=>3000,'hours'=>72));

        Directions::create(array('order'=>4,'code'=>'ПБ','title'=>'Пожарная безопасность','photo_id'=>NULL,'description'=>''));
        $direction_id = Directions::where('order',4)->first()->id;
        Courses::create(array('direction_id'=>$direction_id,'order'=>1,'code'=>'ПБ-1','title'=>'Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>2,'code'=>'ПБ-2','title'=>'Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>3,'code'=>'ПБ-3','title'=>'Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах ','description'=>'','price'=>3000,'hours'=>72));

        Directions::create(array('order'=>5,'code'=>'ИО','title'=>'Инженерное обеспечение','photo_id'=>NULL,'description'=>''));
        $direction_id = Directions::where('order',5)->first()->id;
        Courses::create(array('direction_id'=>$direction_id,'order'=>1,'code'=>'ИО-1','title'=>'Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>2,'code'=>'ИО-2','title'=>'Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>3,'code'=>'ИО-3','title'=>'Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах ','description'=>'','price'=>3000,'hours'=>72));

        Directions::create(array('order'=>6,'code'=>'КГЗ','title'=>'Конструкции гражданских зданий','photo_id'=>NULL,'description'=>''));
        $direction_id = Directions::where('order',6)->first()->id;
        Courses::create(array('direction_id'=>$direction_id,'order'=>1,'code'=>'КГЗ-1','title'=>'Схемы планировочной организации земельного участка, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>2,'code'=>'КГЗ-2','title'=>'Внутренние системы и сети электроснабжения, слаботочные системы, диспетчеризация, автоматизация, управление инженерными системами, в том числе на особо опасных, технически сложных и уникальных объектах.','description'=>'','price'=>3000,'hours'=>72));
        Courses::create(array('direction_id'=>$direction_id,'order'=>3,'code'=>'КГЗ-3','title'=>'Обоснование радиационной и ядерной защиты, в том числе на особо опасных, технически сложных и уникальных объектах ','description'=>'','price'=>3000,'hours'=>72));

    }

}