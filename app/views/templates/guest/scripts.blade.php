@if(Config::get('app.debug'))
    {{ HTML::script('js/plugin/pace/pace.min.js')}}
@endif
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery.min.js');}}"><\/script>')</script>
@if(File::exists(public_path('js/vendor.js')))
{{ HTML::style(Config::get('site.theme_path').'/js/vendor.js') }}
@endif
{{ HTML::script(Config::get('site.theme_path').'/js/index.js') }}
@if(Auth::guest())
{{ HTML::script('js/vendor/jquery-form.min.js') }}
{{ HTML::script('js/vendor/jquery.validate.min.js') }}
{{ HTML::script('js/vendor/jquery.mask.js') }}
{{ HTML::script('js/system/main.js') }}
{{ HTML::script('theme/js/guests.js') }}
<script type="text/javascript">guestFormValidation();</script>
<script>
     $(document).ready(function(){
         $(".phone").inputmask("mask", {"mask": "[+7] (999) 999 99 99","placeholder": "X"});
     });
 </script>
 @endif