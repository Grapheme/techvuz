<?php $header_notification['show'] = FALSE;?>
@if(isOrganizationORListeners())
    @if($active_status = AccountGroupsController::validActiveUserAccount())
        @if($active_status['status'] === FALSE)
        <?php $header_notification = $active_status; ?>
        <?php $header_notification['show'] = TRUE; ?>
        @endif
    @endif
@endif
@if(Session::has('message') && Session::get('message.status') == 'activation')
    <?php $header_notification['message'] = Session::get('message.text');?>
    <?php $header_notification['show'] = TRUE;?>
    <?php $header_notification['code'] = 404;?>
@endif
@if(Session::has('message') && Session::get('message.status') == 'error')
    <?php $header_notification['message'] = Session::get('message.text');?>
    <?php $header_notification['show'] = TRUE;?>
    <?php $header_notification['code'] = 404;?>
@endif
<header class="main-header {{ $header_notification['show'] ? 'notificated' : '' }} clearfix">    
    
    @if (Request::is('/'))
    <div class="moder-logo"></div>
    @else
    <div class="moder-logo"><a class="moder-logo-link" href="{{ URL::route('mainpage') }}"></a></div>
    @endif
    
    <div class="top-dec">
        <div class="top-dec-part part-1"></div>
        <div class="top-dec-part part-2"></div>
        <div class="top-dec-part part-3"></div>
        <div class="top-dec-part part-4"></div>
        <div class="top-dec-part part-5"></div>
        <div class="top-dec-part part-6"></div>
    </div>
    <div class="contact">
        <div class="phone phone-stack">
            <a class="js-phone" data-name="Москва"  data-type="moscow" href="tel:+74997058688">8 (499) 705-86-88</a>
            <a class="js-phone" data-name="Ростов-на-Дону" data-type="rostov" href="tel:+78632990714">8 (863) 299-07-14</a>
            <a class="js-phone" data-type="other" href="tel:+78003338654">8 (800) 333-86-54</a>
        </div>
        <div class="phone-desc phone-links">
            <a class="js-phone-link" data-name="Москва" data-type="moscow" href="#">Москва</a>
            <a class="js-phone-link" data-name="Ростов-на-Дону" data-type="rostov" href="#">Ростов-на-Дону</a>
            <a class="js-phone-link" data-type="other" href="#">Другие регионы</a>
        </div>
    </div>

@if($header_notification['show'])
    @if($header_notification['code'] == 1)
    <div class="notif notif--warning">
        {{ $header_notification['message'] }}
    </div>
    @elseif($header_notification['code'] == 2)
    <div class="notif notif--warning">
        {{ $header_notification['message'] }} {{-- Lang::get('interface.ACCOUNT_EMAIL_STATUS.repeated_sending') --}}
    </div>
    @elseif($header_notification['code'] == 3)
    <div class="notif notif--danger">
        {{ $header_notification['message'] }}
    </div>
    @elseif($header_notification['code'] == 4)
    <div class="notif notif--danger">
        {{ $header_notification['message'] }} {{ Lang::get('interface.ACCOUNT_EMAIL_STATUS.repeated_sending') }}
    </div>
    @elseif($header_notification['code'] == 404)
    <div class="notif notif--danger">
        {{ $header_notification['message'] }}
    </div>
    @endif
@endif

    <div class="auth">
    @if(Auth::guest())
        <a class="btn btn--bordered" href="{{ pageurl('registration') }}">Оформить заявку</a>
        <span class="or-span">или</span>
        <a class="login-link js-login" href="javascript:void(0);">Войти</a>
    @else        
        <a class="btn btn--bordered" href="{{ URL::to(AuthAccount::getStartPage()) }}">Личный кабинет</a>
        <span class="or-span">или</span>
        <a class="login-link" href="{{ URL::route('logout') }}">Выйти</a>
    @endif
    </div>

    <div class="mobile-menu">
        <div class="burger-cont">
            <div class="burger">

            </div>
        </div>
        <div class="mobile-logo">Техвуз.рф</div>
    </div>
</header>