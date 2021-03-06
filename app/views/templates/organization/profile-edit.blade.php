@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <a class="name-dashboard" href="{{ URL::route('dashboard') }}"><h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2></a>
    <div class="edit-employee-anket">
        @include(Helper::acclayout('menu'))
        <h3 class="margin-bottom-30">Редактировать профиль</h3>
        @include(Helper::acclayout('forms.profile'))
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
     });
 </script>
@stop