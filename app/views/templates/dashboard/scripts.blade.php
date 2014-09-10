@if(Config::get('app.use_scripts_local'))
	{{HTML::script('js/vendor/jquery.min.js');}}
	{{HTML::script('js/vendor/jquery-ui.min.js');}}
@else
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery.min.js');}}"><\/script>')</script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<script>if(!window.jQuery.ui){document.write('<script src="{{asset('js/vendor/jquery-ui.min.js')}}"><\/script>');}</script>
@endif
	{{HTML::script('js/vendor/bootstrap.min.js');}}
	{{HTML::script('js/system/main.js');}}
	{{HTML::script('js/vendor/SmartNotification.min.js');}}
	{{HTML::script('js/vendor/jquery.validate.min.js');}}
	{{HTML::script('js/vendor/jquery.maskedinput.min.js');}}
	{{HTML::script('js/vendor/bootstrap-slider.min.js');}}
	{{HTML::script('js/vendor/smartclick.js');}}
	{{HTML::script('js/system/app.js');}}
	{{HTML::script('js/system/messages.js');}}