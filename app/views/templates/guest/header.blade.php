<div class="wrapper">
    <header class="main-header">
    @if (Request::is('/'))
        <h1 class="logo">ТехВУЗ</h1>
    @else
        <div class="logo"><a href="{{ URL::route('mainpage') }}"></a></div>
    @endif
        <nav>
            <ul class="nav-ul">
                <li class="nav-li">
                    <a href="{{ URL::route('page', 'register') }}">Регистрация</a>
                </li>
            </ul>
        </nav>
    </header>
</div>