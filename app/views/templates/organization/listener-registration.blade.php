@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h1>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h1>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="edit-employee-anket">
            <h2 class="h3">Регистрация нового сотрудника</h2 class="h3">
            @include(Helper::acclayout('forms.signup-listener'))
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
{{ HTML::script('js/vendor/jquery-form.min.js') }}
{{ HTML::script('js/vendor/jquery.validate.min.js') }}
{{ HTML::script('js/vendor/jquery.mask.js') }}
{{ HTML::script('js/system/main.js') }}

{{ HTML::script('theme/js/organization.js') }}
<script type="text/javascript">organizationFormValidation();</script>
<script>
     $(document).ready(function(){
         $(".phone").inputmask("mask", {"mask": "[+7] (999) 999 99 99","placeholder": "X"});
         $(".year").inputmask("mask", {"mask": "9999","placeholder": "X"});

         $('.listener-add-form input[name="email"]').keyup( function(e){
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 || key == 37 || key == 38 || key == 39 || key == 40)
                return false;

            this.value = this.value.replace(/[а-яА-яЁё]/i, "");
         });

         $('.listener-add-form input[name="fio"], .listener-add-form input[name="fio_dat"], .listener-add-form input[name="position"], .listener-add-form input[name="education"]').keyup(function(e){
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 || key == 37 || key == 38 || key == 39 || key == 40)
                return false;

            this.value = this.value.replace(/[a-zA-Z0123456789]/i, "");
         });

         $('.listener-add-form input[name="education"], .listener-add-form input[name="education_document_data"], .listener-add-form input[name="specialty"], .listener-add-form input[name="educational_institution"]').keyup( function(e){
            var key = event.keyCode || event.charCode;
            if( key == 8 || key == 46 || key == 37 || key == 38 || key == 39 || key == 40)
                return false;
            this.value = this.value.replace(/[a-zA-Z]/i, "");
         });

     });
 </script>
@stop