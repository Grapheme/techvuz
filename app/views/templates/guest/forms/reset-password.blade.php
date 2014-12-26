{{ Form::open(array('route'=>array('password-reset.update',$token),'class'=>'auth-form registration-form','id'=>'reset-password-form', 'method'=>'PUT')) }}
    {{ Form::hidden('token',$token) }}
    {{ Form::hidden('email',$email) }}
    <fieldset>
        <div class="form-element">
            <label>Пароль</label>{{ Form::password('password',array('id'=>'password')) }}
        </div>
        <div class="form-element">
            <label>Повторите пароль</label>{{ Form::password('password_confirmation',array('id'=>'password_confirmation')) }}
        </div>
    </fieldset>
    <fieldset>
        <div class="form-element">
            <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
                <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сбросить пароль</span>
            </button>
        </div>
    </fieldset>
{{ Form::close() }}