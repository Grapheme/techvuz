
    <!-- Don't forget add to form: ['files' => true] -->
    {{ Helper::ta_($value) }}

    Embed-код:
    <label class="textarea">
        {{ Form::textarea($name . '[embed]', is_object($value) ? $value->embed : null, array('rows' => '3')) }}
    </label>

    <p class="input margin-top-10">
        Preview-изображение:
        <br/>
        @if (@is_object($value->image))
            <label class="checkbox pull-right">
                {{ Form::checkbox($name . '[delete_image]', 1, null, array('style' => 'display:inline-block; width:20px; height:20px;')) }} Удалить
                <i></i>
            </label>
            {{ Helper::d_($value->image->name) }}
            <a href="{{ $value->image->full() }}" target="_blank">
                <img src="{{ $value->image->thumb() }}" style="width:100px" />
            </a>
        @endif
    </p>

    @if (@is_object($value) && $value->id)
        {{ Form::hidden($name . '[video_id]', $value->id) }}
    @endif

    <label class="input margin-top-10">
        {{ Form::file($name . '[image_file]', $params) }}
    </label>