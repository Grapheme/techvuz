@if(Config::get('app.use_scripts_local'))
{{HTML::script('js/vendor/jquery.min.js');}}
{{HTML::script('js/vendor/jquery-ui.min.js');}}
@else
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery.min.js');}}"><\/script>')</script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script>if(!window.jQuery.ui){document.write('<script src="{{asset('js/vendor/jquery-ui.min.js')}}"><\/script>');}</script>
@endif
{{ HTML::script('js/plugin/pace/pace.min.js') }}

@if(File::exists(public_path('js/vendor.js')))
{{ HTML::style(Config::get('site.theme_path').'/js/vendor.js') }}
@endif
{{ HTML::script(Config::get('site.theme_path').'/js/index.js') }}

{{ HTML::script('js/system/main.js') }}
{{ HTML::script('js/vendor/SmartNotification.min.js') }}
{{ HTML::script('js/vendor/jquery.validate.min.js') }}
{{ HTML::script('js/vendor/jquery.mask.js') }}
{{ HTML::script('js/system/app.js') }}
{{ HTML::script('js/system/messages.js') }}

{{ HTML::script('js/vendor/dropzone.min.js') }}
{{ HTML::script(URL::route('collectors.js')) }}

{{ HTML::script('theme/js/moderator.js') }}
<script type="text/javascript">moderatorFormValidation();</script>
<script>
     $(document).ready(function(){
         $(".phone").inputmask("mask", {"mask": "[+7] (999) 999 99 99","placeholder": "X"});
     });
 </script>