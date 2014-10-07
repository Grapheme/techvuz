<header class="main-header clearfix">
    <div class="top-dec">
        <div class="top-dec-part part-1"></div>
        <div class="top-dec-part part-2"></div>
        <div class="top-dec-part part-3"></div>
        <div class="top-dec-part part-4"></div>
        <div class="top-dec-part part-5"></div>
        <div class="top-dec-part part-6"></div>
    </div>
    <div class="contact">
        <div class="phone">
            <a href="tel:+78004400000">8 (800) 440 00 00</a>
        </div>
        <div class="phone-desc">
            Звонок бесплатный
        </div>
    </div>
    <div class="auth">
    @if(Auth::guest())
        <a class="btn btn--bordered" href="{{ URL::route('page', 'registration') }}">Оформить заявку</a>
        <span class="or-span">или</span>
        <a class="login-link js-login" href="javascript:void(0);">Войти</a>
    @else
        @if(isOrganizationORIndividual())
            @if($active_status = AccountGroupsController::validActiveUserAccount())
                @if($active_status['status'] === FALSE)
                <div style="float: left">
                    <span>{{ $active_status['message'] }}</span>
                    <p>Для повторной отправки активационных данных нажмите на <a href="{{ URL::route('activation-repeated-sending-letter') }}">ссылку</a>.</p>
                </div>
                @endif
            @endif
        @endif
        <a class="btn btn--bordered" href="{{ URL::to(AuthAccount::getStartPage()) }}">Личный кабинет</a>
        <span class="or-span">или</span>
        <a class="login-link" href="{{ URL::route('logout') }}">Выйти</a>
    @endif
    </div>
</header>