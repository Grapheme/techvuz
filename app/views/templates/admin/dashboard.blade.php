@extends('templates.'.AuthAccount::getStartPage())
@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="margin-top-10">
            <h1>Добро пожаловать в Egg CMS!</h1>
            <p>Воспользуйтесь меню для перехода к нужному модулю.</p>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="margin-top-10">
    @if (Allow::module('dictionaries'))
         @if(Dictionary::whereSlugValues('actions_history')->values()->count())
            <?php $actionsDictionary = Dictionary::whereSlugValues('actions_types')->values()->lists('name','id');?>
            @foreach(User::whereIn('group_id',array(1,3))->select('name','surname','id')->get() as $user)
                <?php $moderators[$user->id] = $user->name.' '.$user->surname; ?>
            @endforeach
            <header>ВАШИ ДЕЙСТВИЯ</header>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="col-lg-1 text-center" style="white-space:nowrap;">№</th>
                        <th class="col-lg-7 text-center" style="white-space:nowrap;">Действие</th>
                        <th class="col-lg-2 text-center">Пользователь</th>
                        <th class="col-lg-2 text-center">Дата</th>
                    </tr>
                </thead>
                <tbody>
            @foreach(Dictionary::whereSlugValues('actions_history')->values()->take(5) as $value)
                    <?php $value = $value->extract(TRUE);?>
                    <tr class="vertical-middle">
                        <td class="text-center" style="white-space:nowrap;">{{ $value->id }}</td>
                        <td>{{ $value->title }}</td>
                        <td>{{ isset($moderators[$value->user_id]) ? $moderators[$value->user_id] : ' --- ' }}</td>
                        <td>{{ myDateTime::SwapDotDateWithTime($value->created_time) }}</td>
                    </tr>
            @endforeach
                </tbody>
            </table>
         @endif
    @endif
		</div>
	</div>
</div>
@stop
@section('scripts')
@stop