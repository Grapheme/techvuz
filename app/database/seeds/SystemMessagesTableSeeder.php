<?php

class SystemMessagesTableSeeder extends Seeder {

    public function run(){

        Dictionary::create(array('slug'=>'types-system-messages','name'=>'Типы СС','entity'=> 1,'icon_class'=>'fa-bolt','hide_slug'=>1,'make_slug_from_name'=>1,'name_title'=>'Текст сообщения','pagination'=>0,'view_access'=>1,'sort_by'=>NULL,'sort_order_reverse'=>0,'sortable'=>1,'order'=>0));
        Dictionary::create(array('slug'=>'system-messages','name'=>'Системные сообщения','entity'=> 0,'icon_class'=>'fa-bolt','hide_slug'=>1,'make_slug_from_name'=>1,'name_title'=>'Текст сообщения','pagination'=>30,'view_access'=>1,'sort_by'=>'created_at','sort_order_reverse'=>1,'sortable'=>1,'order'=>0));

        $dic_id = Dictionary::where('slug','types-system-messages')->first()->id;
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.approve-email','name'=> 'Подтвердите свой e-mail (пройдите по ссылке, направленной на указанный при регистрации адрес)','order'=>1));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.save-profile','name'=> 'Заполните анкету компании и сможете покупать курсы.','order'=>2));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.register-listeners','name'=> 'Добавьте слушателей — сотрудников, которых нужно обучить.','order'=>3));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.select-courses','name'=> 'Выберите нужные курсы, назначьте слушателей и оформите заказ.','order'=>4));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.account-blocked','name'=> 'Ваша учетная запись заблокирована. За уточнениями обратитесь к администрации системы.','order'=>5));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order-puy','name'=> 'Для начала обучения оплатите счет <a href="[link]">№[order]</a>.','order'=>6));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.study.begin','name'=> 'Начало обучения — сотрудник [listener] открыл первую лекцию курса [course]','order'=>7));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.study.control','name'=> 'Контрольные точки — курс [course]: сотрудник [listener] успешно завершил промежуточное тестирования с результатом [percent]%.','order'=>8));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.study.fail','name'=> 'Неудачные попытки — курс [course]: сотрудник [listener] неудачно завершил промежуточное/итоговое тестирование с результатом [percent]%.','order'=>9));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.study.finish','name'=> 'Итоговый результат — сотрудник [listener] завершил обучение по курсу [course]. Итоговое тестирование завершено с результатом [percent]%.','order'=>10));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.new','name'=> 'Новый заказ ожидает отправки','order'=>11));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.approve','name'=> 'Заказ [order] ожидает подтверждения администратором','order'=>12));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.not-puy-not-access','name'=> 'Заказ [order] не оплачен, доступ к обучению не предоставлен.','order'=>13));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.not-puy-yes-access','name'=> 'Заказ [order] не оплачен, но доступ к обучению предоставлен.','order'=>14));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.part-puy-not-access','name'=> 'Заказ [order] оплачен частично, доступ к обучению не предоставлен.','order'=>15));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.part-puy-yes-access','name'=> 'Заказ [order] оплачен частично, но доступ к обучению предоставлен.','order'=>16));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.yes-puy-yes-access','name'=> 'Заказ [order] оплачен, доступ к обучению предоставлен.','order'=>17));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.register-organization','name'=> 'Зарегистрирована организация [organization]','order'=>18));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.update-profile-organization','name'=> 'Организация [organization] обновила регистрационные данные','order'=>19));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.register-listener','name'=> 'Организация [organization] зарегистрировала [listener] как сотрудника','order'=>20));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.update-profile-listener','name'=> 'Сотрудник организации [organization], [listener],  обновил(-а) регистрационные данные','order'=>21));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.order.new','name'=> 'Оформлен заказ №[order]','order'=>22));
    }
}