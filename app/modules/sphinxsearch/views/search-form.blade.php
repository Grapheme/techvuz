{{ Form::open(array('url'=>URL::route('search_request'),'method'=>'post','class'=>'main-search')) }}
<fieldset>
    <input type="text" name="search_request" class="search-input" value="{{ Input::get('query') }}">
    <button type="submit"><span class="icon icon-search"></span></button>
</fieldset>
{{ Form::close() }}