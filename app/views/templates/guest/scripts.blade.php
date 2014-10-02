@if(Config::get('app.debug'))
    {{ HTML::script('js/plugin/pace/pace.min.js')}}
@endif
@if(File::exists(public_path('js/vendor.js')))
{{ HTML::style(Config::get('site.theme_path').'/js/vendor.js') }}
@endif
{{ HTML::script(Config::get('site.theme_path').'/js/main.js') }}

{{ HTML::script('js/vendor/jquery-form.min.js') }}
{{ HTML::script('js/vendor/jquery.validate.min.js') }}
{{ HTML::script('js/vendor/jquery.mask.js') }}
{{ HTML::script('js/system/main.js') }}
@if(Auth::guest())
{{ HTML::script('theme/js/guests.js') }}
<script type="text/javascript">guestFormValidation();</script>
@endif
<script>
     $(document).ready(function(){
         $(".phone").inputmask("mask", {"mask": "[+7] (999) 999 99 99","placeholder": "X"});
         $(".email").inputmask({
                 mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
                 greedy: false,
                 onBeforePaste: function (pastedValue, opts) {
                     pastedValue = pastedValue.toLowerCase();
                     return pastedValue.replace("mailto:", "");
                 },
                 definitions: {
                     '*': {
                         validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
                         cardinality: 1,
                         casing: "lower"
                     }
                 }
         });
     });
 </script>