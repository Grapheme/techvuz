
    @if(Config::get('app.use_scripts_local'))
        {{ HTML::scriptmod('js/vendor/jquery.min.js') }}
    @else
        {{ HTML::script("//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js") }}
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
    @endif

    {{ HTML::scriptmod("js/index.js") }}
    {{ HTML::scriptmod("js/app.js") }}

    {{ HTML::script("js/vendor/jquery.validate.min.js") }}
    {{ HTML::script("js/vendor/jquery-form.min.js") }}
