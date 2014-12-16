<?php

class SystemMessagesTableSeeder extends Seeder {

    public function run(){

        Dictionary::create(array('slug'=>'types-system-messages','name'=>'Типы СС','entity'=> 1,'icon_class'=>'fa-bolt','hide_slug'=>1,'make_slug_from_name'=>1,'name_title'=>'Текст сообщения','pagination'=>0,'view_access'=>1,'sort_by'=>NULL,'sort_order_reverse'=>0,'sortable'=>1,'order'=>0));
        Dictionary::create(array('slug'=>'system-messages','name'=>'Системные сообщения','entity'=> 0,'icon_class'=>'fa-bolt','hide_slug'=>1,'make_slug_from_name'=>1,'name_title'=>'Текст сообщения','pagination'=>30,'view_access'=>1,'sort_by'=>'created_at','sort_order_reverse'=>1,'sortable'=>1,'order'=>0));

        $dic_id = Dictionary::where('slug','types-system-messages')->first()->id;

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.approve-email','name'=> 'Подтвердите email и сможете заказывать курсы. Ссылка отправлена на Вашу почту.','order'=>1));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.register-listeners','name'=> 'Необходимо добавить слушателей – сотрудников Вашей организации, которых необходимо обучить.','order'=>3));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.select-courses','name'=> 'Необходимо выбрать курс(ы) и оформить заказ.','order'=>4));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.account-blocked','name'=> 'Ваша учетная запись заблокирована. За уточнениями обратитесь к администрации системы.','order'=>5));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order-puy','name'=> 'Ваш заказ <a href="[order_link]">№[order]</a> оформлен. Для получения доступа к лекционным материалам Вам необходимо оплатить <a href="[document_link]">счет</a>. Доступ предоставляется в течение 2 часов с момента поступления денежных средств на расчетный счет образовательного портала ТЕХВУЗ.РФ','order'=>6));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.study.begin','name'=> '[listener] приступил(а) к обучению по программе [course]. К итоговому тестированию по программе [course] можно будет приступить через 72 академических часа.','order'=>7));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.study.control','name'=> '[listener] успешно завершил(а) промежуточное тестирования по программе [course] с результатом [percent]%.','order'=>8));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.study.fail-control','name'=> '[listener] неудачно завершил(а) промежуточное тестирование по программе [course] с результатом [percent]%.','order'=>9));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.study.fail-finish','name'=> '[listener] неудачно завершил(а) итоговое тестирование по программе [course] с результатом [percent]%.','order'=>9));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.study.finish','name'=> '[listener] прошел(а) итоговое тестирование по программе [course] с результатом [percent]%.','order'=>10));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.part-puy-not-access','name'=> 'Заказ <a href="[link]">№[order]</a> оплачен частично.','order'=>15));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.yes-puy-yes-access','name'=> 'Заказ <a href="[link]">№[order]</a> оплачен.','order'=>17));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.register-organization','name'=> 'Зарегистрирована организация [organization]','order'=>19));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.update-profile-organization','name'=> 'Организация [organization] обновила регистрационные данные','order'=>20));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.register-listener','name'=> '[listener] добавлен(а) к слушателям – сотрудникам [organization]','order'=>21));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.update-profile-listener','name'=> 'Сотрудник организации [organization], [listener],  обновил(а) регистрационные данные','order'=>22));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.order.new','name'=> 'Оформлен заказ №[order]','order'=>23));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.order.closed','name'=> 'Заказ №[order] закрылся','order'=>24));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.closed','name'=> 'Заказ <a href="[link]">№[order]</a> закрыт. Все сотрудники Вашей организации успешно прошли итоговые тестирования по заданным курсам.','order'=>25));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order-puy-no-approve','name'=> 'Ваш заказ <a href="[link]">№[order]</a> оформлен. Для получения договора на обучение и счета на оплату платных образовательных услуг необходимо дождаться окончания проверки администратором сайта. Проверка занимает не более 12 часов с момента активации личного кабинета','order'=>26));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'account.approved-email','name'=> 'Ваш личный кабинет активирован. Происходит проверка администратором сайта','order'=>27));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'account.approved-profile','name'=> 'Вы успешно прошли проверку администратора сайта ТЕХВУЗ.РФ. Теперь у Вас появился доступ к документации (договоры, счета, акты).','order'=>28));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.closed-documents','name'=> 'Для получения сканированных копий документов Ваших сотрудников пройдите по <a href="[link]">ссылке</a>. Для получения оригиналов документов отправьте Почтой России, подписанные с Вашей стороны в 2-х экземплярах договор на оказание платных образовательных услуг и акт о выполнении работ.','order'=>29));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'listener.approved-email','name'=> 'Ваш личный кабинет активирован.','order'=>30));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.approved-email','name'=> 'Ваш личный кабинет активирован. Происходит проверка администратором сайта.<br>Необходимо добавить слушателей – сотрудников Вашей организации, которых необходимо обучить.<br>Необходимо выбрать курс(ы) и оформить заказ.','order'=>33));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'individual.approved-email','name'=> 'Ваш личный кабинет активирован. Происходит проверка администратором сайта.<br>Необходимо выбрать курс(ы) и оформить заказ.','order'=>34));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'listener.study-access','name'=> 'Вам предоставлен доступ к обучению по курсу <a href="[link]">[course]</a>. Вы сможете приступить к итоговому тестированию [date].','order'=>31));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'listener.study-finish','name'=> 'Вы завершили обучение по курсу [course].','order'=>32));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'organization.order.closed-join','name'=> 'Заказ <a href="[link]">№[order]</a> закрыт. Все сотрудники Вашей организации успешно прошли итоговые тестирования по заданным курсам.<br>Для получения сканированных копий документов Ваших сотрудников пройдите по <a href="[link]">ссылке</a>. Для получения оригиналов документов отправьте Почтой России, подписанные с Вашей стороны в 2-х экземплярах договор на оказание платных образовательных услуг и акт о выполнении работ.','order'=>33));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'individual.order.closed-join','name'=> 'Заказ <a href="[link]">№[order]</a> закрыт. Вы успешно прошли итоговые тестирования по заданным курсам.<br>Для получения сканированных копий документов пройдите по <a href="[link]">ссылке</a>. Для получения оригиналов документов отправьте Почтой России, подписанные с Вашей стороны в 2-х экземплярах договор на оказание платных образовательных услуг и акт о выполнении работ.','order'=>34));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'moderator.update-profile-individual','name'=> 'Индивидуальный слушатель [listener] обновил регистрационные данные','order'=>35));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'individual.order-puy-no-approve','name'=> 'Ваш заказ <a href="[link]">№[order]</a> оформлен. Для получения договора на обучение и счета на оплату платных образовательных услуг необходимо дождаться окончания проверки администратором сайта. Проверка занимает не более 12 часов с момента активации личного кабинета','order'=>36));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'individual.order-puy','name'=> 'Ваш заказ <a href="[order_link]">№[order]</a> оформлен. Для получения доступа к лекционным материалам Вам необходимо оплатить <a href="[document_link]">счет</a>. Доступ предоставляется в течение 2 часов с момента поступления денежных средств на расчетный счет образовательного портала ТЕХВУЗ.РФ','order'=>37));

        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'individual.order.part-puy-not-access','name'=> 'Заказ <a href="[link]">№[order]</a> оплачен частично.','order'=>38));
        DicVal::create(array('dic_id'=>$dic_id,'slug'=>'individual.order.yes-puy-yes-access','name'=> 'Заказ <a href="[link]">№[order]</a> оплачен.','order'=>39));

    }
}