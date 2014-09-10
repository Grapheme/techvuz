<header id="header">
	<div id="logo-group">
		<span id="logo"><a href="{{ url(AuthAccount::getStartPage()) }}">Личный кабинет</a></span>
	</div>
	<div class="pull-right">
		<div id="hide-menu" class="btn-header pull-right">
			<span> <a href="javascript:void(0);" title=""><i class="fa fa-reorder"></i></a> </span>
		</div>
		<div id="logout" class="btn-header transparent pull-right">
			<span> <a href="{{url('logout')}}" title="Завершение сеанса"><i class="fa fa-sign-out"></i></a> </span>
		</div>
	</div>
</header>