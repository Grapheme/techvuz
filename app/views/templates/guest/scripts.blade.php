@if(Config::get('app.debug'))
    {{HTML::script('js/plugin/pace/pace.min.js');}}
@endif
{{HTML::script(Config::get('site.theme_path').'/scripts/vendor.js');}}
{{HTML::script(Config::get('site.theme_path').'/scripts/main.js');}}