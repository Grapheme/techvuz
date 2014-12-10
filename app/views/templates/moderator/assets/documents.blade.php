<ul class="margin-top-20">
    <li><a href="{{ URL::route('moderator-order-contract',array('order_id'=>$order->id,'format'=>'pdf')) }}">Договор</a></li>
    <li><a href="{{ URL::route('moderator-order-invoice',array('order_id'=>$order->id,'format'=>'pdf')) }}">Счет</a></li>
    <li><a href="{{ URL::route('moderator-order-act',array('order_id'=>$order->id,'format'=>'pdf')) }}">Акт</a></li>
    <li><a href="{{ URL::route('moderator-order-request',array('order_id'=>$order->id,'format'=>'pdf')) }}">Заявки</a></li>
    <li><a href="{{ URL::route('moderator-order-enrollment',array('order_id'=>$order->id,'format'=>'pdf')) }}">Приказ о зачислении</a></li>
    <li><a href="{{ URL::route('moderator-order-completion',array('order_id'=>$order->id,'format'=>'pdf')) }}">Приказ об окончании</a></li>
    <li><a href="{{ URL::route('moderator-order-class-schedule',array('order_id'=>$order->id,'format'=>'pdf')) }}">Расписание занятий</a></li>
    <li><a href="{{ URL::route('moderator-order-statements',array('order_id'=>$order->id,'format'=>'pdf')) }}">Заявления</a></li>
    <li><a href="{{ URL::route('moderator-order-explanations',array('order_id'=>$order->id,'format'=>'pdf')) }}">Пояснения к документам</a></li>
    <li><a href="{{ URL::route('moderator-order-browsing-history',array('order_id'=>$order->id,'format'=>'pdf')) }}">Журнал посещений</a></li>
    <li><a href="{{ URL::route('moderator-order-attestation-sheet',array('order_id'=>$order->id,'format'=>'pdf')) }}">Аттестационные ведомости</a></li>
    <li><a href="{{ URL::route('moderator-order-result-certification',array('order_id'=>$order->id,'format'=>'pdf')) }}">Результаты аттестации</a></li>
</ul>