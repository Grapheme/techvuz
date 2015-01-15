<?php
    $accounts = array('0'=>'Все заказчики');
    $directions = array('0'=>'Все направления');
    foreach(User_organization::orderBy('title')->lists('title','id') as $account_id => $name):
        $accounts[$account_id] = $name;
    endforeach;
    foreach(User_individual::orderBy('fio')->lists('fio','id') as $account_id => $name):
        $accounts[$account_id] = $name;
    endforeach;
    foreach(Directions::lists('title','id') as $direction_id => $title):
        $directions[$direction_id] = $title;
    endforeach;
?>
{{ Form::open(array('url'=>URL::route('moderator-statistic-set-period'), 'class'=>'auth-form registration-form clearfix margin-bottom-30', 'id'=>'statistic-form', 'role'=>'form', 'method'=>'post')) }}
    <div class="form-element">
        <label>Заказчик</label>{{ Form::select('account_id',$accounts,$account_selected,array('autocomplete'=>'off')) }}
    </div>
    <div class="form-element">
        <label>Направления</label>{{ Form::select('direction_id',$directions,$direction_selected,array('autocomplete'=>'off')) }}
    </div>
    <div class="form-element">
        <label>От</label>{{ Form::text('period_begin',$period_begin, array('id'=>'select-period-begin','class' => 'datepicker text-center','autocomplete'=>'off','data-mask'=>'99.99.9999')) }}
    </div>
    <div class="form-element">
        <label>До</label>{{ Form::text('period_end', $period_end, array('id'=>'select-period-end','class' => 'datepicker text-center','autocomplete'=>'off','data-mask'=>'99.99.9999')) }}
    </div>
    <footer>
        <button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Показать</span>
        </button>
    </footer>
{{ Form::close() }}