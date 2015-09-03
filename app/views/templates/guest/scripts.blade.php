@if(Config::get('app.debug'))
    {{ HTML::script('js/plugin/pace/pace.min.js')}}
@endif
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery.min.js');}}"><\/script>')</script>
@if(File::exists(public_path('js/vendor.js')))
{{ HTML::style(Config::get('site.theme_path').'/js/vendor.js') }}
@endif
{{ HTML::script(Config::get('site.theme_path').'/js/vendor/fotorama.js') }}
{{ HTML::script(Config::get('site.theme_path').'/js/index.js') }}
{{ HTML::script('js/vendor/jquery-form.min.js') }}

<script>
    $('.dynamic-banners').fotorama({
        width: 670,
        height: 193,
        nav: false,
        autoplay: 3000,
        arrows: false,
        swipe: false,
        loop: true
    });
</script>

@if(Auth::guest())
{{ HTML::script('js/vendor/jquery.validate.min.js') }}
{{ HTML::script('js/vendor/jquery.mask.js') }}
{{ HTML::script('js/system/main.js') }}
{{ HTML::script('theme/js/guests.js') }}
<script type="text/javascript">guestFormValidation();</script>
<script>
    $(document).ready(function(){

        setTimeout(function(){ Popup.show('quick'); return false; }, 15000);

        $(".phone").inputmask("mask", {"mask": "[+7] (999) 999 99 99","placeholder": "_"});
        // $('.registration-form input[name="ogrn"]').inputmask("mask", {"mask": "9999999999999","placeholder": "_"});
        // $('.registration-form input[name="kpp"]').inputmask("mask", {"mask": "999999999","placeholder": "_"});
        // $('.registration-form input[name="inn"]').inputmask("mask", {"mask": "9999999999","placeholder": "_"});
        // $('.registration-form input[name="bik"]').inputmask("mask", {"mask": "999999999","placeholder": "_"});

        $('.registration-form input[name="account_number"], .registration-form input[name="bik"], .registration-form input[name="account_kor_number"]').keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        $('.registration-form input[name="ogrn"], .registration-form input[name="kpp"], .registration-form input[name="inn"], .registration-form input[name="bik"]').keyup( function(event){
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 || key == 37 || key == 38 || key == 39 || key == 40)
                return false;

            var start = this.selectionStart;            

            this.value = this.value.replace(/[^0123456789]/i, "");

            this.setSelectionRange(start, start);
        });

        $('.registration-form input[name="email"]').keyup(function(event) {
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 || key == 37 || key == 38 || key == 39 || key == 40)
                return false;

            var start = this.selectionStart;  

            this.value = this.value.replace(/[а-яА-яЁё]/i, "");

            this.setSelectionRange(start, start);
        });
        $('.registration-form input[name="title"], .registration-form input[name="educational_institution"], .registration-form input[name="specialty"], .registration-form input[name="document_education"], .registration-form input[name="education"], .registration-form input[name="position"], .registration-form input[name="code"], .registration-form input[name="passport_date"], .registration-form input[name="passport_data"], .registration-form input[name="uraddress"], .registration-form input[name="postaddress"], .registration-form input[name="bank"]').keyup( function(event){
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 || key == 37 || key == 38 || key == 39 || key == 40)
                return false;

            var start = this.selectionStart;

            this.value = this.value.replace(/[a-zA-Z]/i, "");

            this.setSelectionRange(start, start);
        });
        $('.registration-form input[name="fio_manager"], .registration-form input[name="fio_manager_rod"], .registration-form input[name="manager"], .registration-form input[name="name"], .registration-form input[name="fio"], .registration-form input[name="fio_rod"]').keyup( function(event) {
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 || key == 37 || key == 38 || key == 39 || key == 40)
                return false;

            var start = this.selectionStart;

            this.value = this.value.replace(/[^а-яА-ЯеЁ -]/i, "");

            this.setSelectionRange(start, start);
        });

        $('.registration-form input[name="statutory"]').keyup( function(event){
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 || key == 37 || key == 38 || key == 39 || key == 40)
                return false;
            var start = this.selectionStart;

            this.value = this.value.replace(/[a-zA-Z]/i, "");

            this.setSelectionRange(start, start);
        });
    });

    
     $('[data-toggle="tooltip"]').tooltip();
     $('.course-in-progress a').click( function(e){
        e.preventDefault();
     });
     $('.direction-in-progress .direction-link').click( function(e){
        e.preventDefault();
     });
     $('.accordion-header.direction-in-progress').addClass('ui-state-disabled');
</script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter28373826 = new Ya.Metrika({id:28373826,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    trackHash:true});
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<script src="//api-maps.yandex.ru/2.0/?load=package.standard&lang=ru-RU" type="text/javascript"></script>
<script src="http://api-maps.yandex.ru/2.0/?load=package.standard&lang=ru-RU" type="text/javascript"></script>
<noscript><div><img src="//mc.yandex.ru/watch/28373826" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
@endif