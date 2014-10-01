{{ Form::open(array('action'=>'RemindersController@store','role'=>'form','class'=>'','id'=>'restore-password-form')) }}
    <div class="form-element">
        <label>Email</label>{{ Form::email('email') }}
    </div>
    <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue pull-right btn-form-submit">
        <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Восстановить</span>
    </button>
{{ Form::close() }}