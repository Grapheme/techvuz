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
            <a href="tel:+78632990714">8 (863) 299 07 14</a>
        </div>
        <div class="phone-desc">
            Звонок бесплатный
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
        <a class="btn btn--bordered" href="{{ URL::route('page', 'registration') }}">Оформить заявку</a>
        <span class="or-span">или</span>
        <a class="login-link js-login" href="javascript:void(0);">Войти</a>
    @else        
        <a class="btn btn--bordered" href="{{ URL::to(AuthAccount::getStartPage()) }}">Личный кабинет</a>
        <span class="or-span">или</span>
        <a class="login-link" href="{{ URL::route('logout') }}">Выйти</a>
    @endif
    </div>
</header>