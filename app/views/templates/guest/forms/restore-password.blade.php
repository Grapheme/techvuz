<div class="popup popup-restore" data-popup="restore">
    <div class="popup-close js-popup-close">
        <span class="icon icon-cancel"></span>
    </div>
    <div class="popup-head">
        Восстановление пароля
    </div>
    <div class="popup-body">
    {{ Form::open(array('route'=>'password-reset.store','role'=>'form','class'=>'auth-form registration-form','id'=>'restore-password-form')) }}
        <div class="form-element">
            <label>Email</label>{{ Form::email('email') }}
        </div>
        <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue pull-right btn-form-submit">
            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Восстановить</span>
        </button>
    {{ Form::close() }}
    </div>
</div>