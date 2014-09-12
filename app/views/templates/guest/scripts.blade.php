@if(Config::get('app.debug'))
    {{HTML::script('js/plugin/pace/pace.min.js');}}
@endif
@if(Config::get('app.use_scripts_local'))
    {{ HTML::scriptmod('js/vendor/jquery.min.js') }}
    {{ HTML::scriptmod('js/vendor/jquery-ui.min.js') }}
@else
    {{ HTML::script("//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js") }}
    {{ HTML::script("//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js") }}
@endif

{{HTML::script('js/vendor/bootstrap.min.js');}}
{{HTML::script('js/system/main.js');}}
{{HTML::script('js/vendor/SmartNotification.min.js');}}
{{ HTML::script("js/vendor/jquery.validate.min.js") }}
{{HTML::script('js/system/messages.js');}}