@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<main class="cabinet order-page">
    <?php $account = User_individual::where('id',Auth::user()->id)->first(); ?>
    <h2>{{ $account->fio }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div>
            <div class="orders-li-head">
                <h1>Заказ №{{ getOrderNumber($order) }}</h1>
                <?php $order_price = $paymentNumbersPrice = 0; ?>
                @foreach($order->listeners as $listener)
                <?php $order_price += $listener->price;?>
                @endforeach
                @if($order->payment_numbers->count())
                    @foreach($order->payment_numbers as $payment_number)
                    <?php $paymentNumbersPrice+=$payment_number->price;?>
                    @endforeach
                @endif

                <div class="orders-status">
                    {{ $order->payment->title }}
                </div>
            </div>
            <div class="orders-li-body">
                <div class="orders-price">
                    <span class="start-price">{{ number_format($order_price,0,'.',' ')  }}.-</span> | <span class="end-price">{{ number_format($paymentNumbersPrice,0,'.',' ')  }}.–</span>
                </div>
                <div class="orders-date">
                    Заказ создан: {{ $order->created_at->timezone(Config::get('site.time_zone'))->format("d.m.Y в H:i") }}
                </div>
            </div>
        </div>

        @include(Helper::acclayout('assets.documents'))

        <h3>Состав заказа</h3>
        <table class="tech-table payments-table margin-bottom-30">
            <tr>
                <th> Название курса </th>
                <th> Сумма </th>
                <th> Удостоверение </th>
            </tr>
        <?php
        $courses = $courses_list = array();
        foreach($order->listeners as $course):
            $courses_list[$course->course_id]['course']['id'] = $course->course->id;
            $courses_list[$course->course_id]['course']['code'] = $course->course->code;
            $courses_list[$course->course_id]['course']['url'] = $course->course->seo->url;
            $courses_list[$course->course_id]['course']['title'] = $course->course->title;
            $courses_list[$course->course_id]['course']['price'] = $course->course->price;
            $courses_list[$course->course_id]['listeners'][] = $course;
        endforeach;
        foreach($courses_list as $course_id => $course):
            $courses[$course_id]['course'] = $course['course'];
            foreach($course['listeners'] as $index => $listener):
                $courses[$course_id]['listeners'][$index]['id'] = $listener->id;
                $courses[$course_id]['listeners'][$index]['price'] = $listener->price;
                $courses[$course_id]['listeners'][$index]['start_status'] = $listener->start_status;
                $courses[$course_id]['listeners'][$index]['start_date'] = $listener->start_date;
                $courses[$course_id]['listeners'][$index]['access_status'] = $listener->access_status;
                $courses[$course_id]['listeners'][$index]['over_status'] = $listener->over_status;
            endforeach;
        endforeach;
        ?>
        @foreach($courses as $course_id => $course)
            @if(count($course['listeners']))
                @foreach($course['listeners'] as $index => $listener)
            <tr>
                <td class="vertical-top">
                @if($listener['access_status'] == 1 && $listener['over_status'] == 0)
                    <a href="{{ URL::route('individual-study-course',$listener['id'].'-'.BaseController::stringTranslite($course['course']['title'],100)) }}">{{ $course['course']['code'] }}. {{{ $course['course']['title'] }}}</a>
                @else
                    <a href="{{ URL::route('course-page',$course['course']['url']) }}">
                        {{ $course['course']['code'] }}. {{{ $course['course']['title'] }}}
                    </a>
                @endif
                </td>
                <td class="vertical-top purchase-price">{{ number_format($listener['price'],0,'.',' ') }}.-</td>
                <td class="vertical-top">
                @if($account->moderator_approve == 0)
                    Проверяет администратор
                @elseif($listener['start_status'] == 0 && $listener['over_status'] == 0)
                    Не обучается
                @elseif($listener['start_status'] == 1 && $listener['over_status'] == 1 && $account->moderator_approve)
                    <a href="{{ URL::route('individual-listener-order-certificate',array('order_id'=>$order->id,'course_id'=>$listener['id'],'listener_id'=>Auth::user()->id,'format'=>'pdf')) }}">Просмотреть</a>
                @elseif($listener['start_status'] == 1 && $listener['over_status'] == 0)
                    Обучение не завершено
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