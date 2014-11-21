{{ Form::open(array('url'=>URL::route('moderator-statistic-set-period'), 'class'=>'smart-form', 'id'=>'statistic-form', 'role'=>'form', 'method'=>'post')) }}
    <section class="col col-6">
        <div class="well">
            <fieldset>
                <section class="col-6">
                    <label class="label">Период от:</label>
                    <label class="input">
                        {{ Form::text('period_begin',$period_begin, array('id'=>'select-period-begin','class' => 'datepicker text-center','autocomplete'=>'off','data-mask'=>'99.99.9999')) }}
                    </label>
                </section>
                <section class="col-6">
                    <label class="label">Период до:</label>
                    <label class="input">
                        {{ Form::text('period_end', $period_end, array('id'=>'select-period-end','class' => 'datepicker text-center','autocomplete'=>'off','data-mask'=>'99.99.9999')) }}
                    </label>
                </section>
            </fieldset>
            <footer>
                <button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                    <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
                </button>
            </footer>
        </div>
    </section>
{{ Form::close() }}