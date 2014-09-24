
    <!-- Don't forget add to form: ['files' => true] -->
    @if (is_object($value))
    <p style="margin: 5px 0;">

        <label class="checkbox pull-right">
            {{ Form::checkbox($name . '[delete]', 1, null, array('style' => 'display:inline-block; width:20px; height:20px;')) }} Удалить
            <i></i>
        </label>

        <i class="fa fa-fw fa-save"></i>
        <a href="{{ $value->path }}" target="_blank" download="{{ $value->original_name ?: '' }}">{{ $value->original_name ? $value->original_name : basename($value->path) }}</a>

        {{ Form::hidden($name . '[upload_id]', $value->id) }}
    </p>
    @endif
    {{ Form::file($name . '[file]', $params) }}
