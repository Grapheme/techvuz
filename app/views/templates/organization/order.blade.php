@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<main class="cabinet">
    <h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <h3>Заказ №{{ $order->number }}</h3> {{ $order->created_at->format("d.m.Y в H:i") }}
        <?php $order_price = 0;?>
        @foreach($order->listeners as $listener)
        <?php $order_price += $listener->price;?>
        @endforeach
        <div>Сумма: {{ number_format($order_price,0,'.',' ') }} руб.</div>
        <div>Статус: {{ $order->payment->title }}</div>
        <div>
            Документы:
            @include(Helper::acclayout('assets.documents'))
        </div>
        <h3>Состав заказа</h3>
        <table class="tech-table payments-table margin-bottom-30">
            <tr>
                <th> Курс </th>
                <th> Сотрудник </th>
                <th> Стоимость </th>
                <th> Удостоверение </th>
            </tr>
        <?php
        $courses = $courses_list = array();
        foreach($order->listeners as $course):
            $courses_list[$course->course_id]['course']['id'] = $course->course->id;
            $courses_list[$course->course_id]['course']['code'] = $course->course->code;
            $courses_list[$course->course_id]['course']['title'] = $course->course->title;
            $courses_list[$course->course_id]['course']['price'] = $course->course->price;
            $courses_list[$course->course_id]['listeners'][] = $course;
        endforeach;
        foreach($courses_list as $course_id => $course):
            $courses[$course_id]['course'] = $course['course'];
            foreach($course['listeners'] as $index => $listener):
                $courses[$course_id]['listeners'][$index]['id'] = $listener->user_listener->id;
                $courses[$course_id]['listeners'][$index]['price'] = $listener->price;
                $courses[$course_id]['listeners'][$index]['fio'] = $listener->user_listener->fio;
                $courses[$course_id]['listeners'][$index]['over_status'] = $listener->over_status;
            endforeach;
        endforeach;
        ?>
        @foreach($courses as $course_id => $course)
            @if(count($course['listeners']))
                @foreach($course['listeners'] as $index => $listener)
            <tr>
                @if($index == 0)
                <td rowspan="{{ count($course['listeners']) }}">{{ $course['course']['code'] }}. {{{ $course['course']['title'] }}}</td>
                @endif
                <td>
                    <a href="{{ URL::route('company-listener-profile',$listener['id']) }}">{{ $listener['fio'] }}</a>
                </td>
                <td class="purchase-price">{{ $listener['price'] }} руб.</td>
                <td>
                @if($listener['over_status'] == 1)
                    <a href="{{ URL::route('company-order-certificate-first',array('order_id'=>$order->id,'course_id'=>$course['course']['id'],'listener_id'=>$listener['id'])) }}">Просмотреть</a>
                @endif
                </td>
            </tr>
                @endforeach
            @endif
        @endforeach
        </table>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop