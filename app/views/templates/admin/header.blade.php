<header id="header">
	<div id="logo-group">
		<a class="logo" href="{{ URL::to(AuthAccount::getStartPage()) }}">
            <i class="fa fa-home"></i>
            Панель управления
        </a>
	</div>
	<div class="pull-right">
		<div id="logout" class="btn-header transparent pull-right">
			{{ Auth::user()->email }} &nbsp;
            <a class="logout-text" href="{{ URL::route('logout') }}" title="Завершение сеанса">
                <i class="fa fa-sign-out"></i> выйти
            </a>
		</div>
	</div>
</header>