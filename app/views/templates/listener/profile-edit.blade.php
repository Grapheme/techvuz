@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <?php $account = User_listener::where('id',Auth::user()->id)->with('organization')->first(); ?>
    <h1>{{ $account->fio }}</h1>
    <p class="style-light style-italic">{{ $account->organization->title }}</p>
    <div class="edit-employee-anket">
    @if(Listener::where('user_id',Auth::user()->id)->pluck('approved'))
        @include(Helper::acclayout('menu'))
        <h3 class="margin-bottom-30">Редактировать профиль</h3>
    @else
        <h3 class="margin-bottom-30">Подтверждение регистрационных данных</h3>
    @endif
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