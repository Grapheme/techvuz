<?php
#Helper::dd(AuthAccount::getStartPage());
?>
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
@stop


@section('scripts')
@stop

