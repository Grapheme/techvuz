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
        $(".phone").inputmask("mask", {"mask": "[+7] (999) 999 99 99","placeholder": "_"});
        // $('.registration-form input[name="ogrn"]').inputmask("mask", {"mask": "9999999999999","placeholder": "_"});
        // $('.registration-form input[name="kpp"]').inputmask("mask", {"mask": "999999999","placeholder": "_"});
        // $('.registration-form input[name="inn"]').inputmask("mask", {"mask": "9999999999","placeholder": "_"});
        $('.registration-form input[name="bik"]').inputmask("mask", {"mask": "999999999","placeholder": "_"});

        $('.registration-form input[name="account_number"], .registration-form input[name="bik"], .registration-form input[name="account_kor_number"]').keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
        $('.registration-form input[name="email"]').keyup(function(e) {
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 || key == 37 || key == 38 || key == 39 || key == 40)
                return false;

            this.value = this.value.replace(/[а-яА-яЁё]/i, "");
        });
        $('.registration-form input[name="title"], .registration-form input[name="uraddress"], .registration-form input[name="postaddress"], .registration-form input[name="bank"]').keyup( function(e){
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 || key == 37 || key == 38 || key == 39 || key == 40)
                return false;

            this.value = this.value.replace(/[a-zA-Z]/i, "");
        });
        $('.registration-form input[name="fio_manager"], .registration-form input[name="fio_manager_rod"], .registration-form input[name="manager"], .registration-form input[name="name"], .registration-form input[name="fio"], .registration-form input[name="fio_rod"]').keyup( function(e) {
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 || key == 37 || key == 38 || key == 39 || key == 40)
                return false;

            this.value = this.value.replace(/[^а-яА-ЯеЁ -]/i, "");
        });
    });
</script>
@endif