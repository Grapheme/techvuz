<div class="popup popup-login" data-popup="login">
    <div class="popup-close js-popup-close">
        <span class="icon icon-cancel"></span>
    </div>
    <div class="popup-head">
        Авторизуйтесь
    </div>
    <div class="popup-body">
        {{ Form::open(array('route'=>'signin','role'=>'form','class'=>'auth-form registration-form','id'=>'signin-form')) }}
            {{ Form::hidden('remember',1) }}
            <div class="form-element">
                <label>Email</label>{{ Form::email('login') }}
            </div>
            <div class="form-element">
                <label>Пароль</label>{{ Form::password('password') }}
                <div class="forgot-pass">
                    <a class="js-forgot-pass">Забыли пароль?</a>
                </div>
            </div>
            <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue pull-right btn-form-submit">
                <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Войти</span>
            </button>
        {{ Form::close() }}
    </div>
</div>