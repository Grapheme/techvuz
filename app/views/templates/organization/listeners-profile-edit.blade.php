@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2>
    <div class="edit-employee-anket">
        @include(Helper::acclayout('menu'))
        <div class="pull-right margin-top-20">
            {{ Form::open(array('url'=>URL::route('organization-listener-profile-delete',$listener->id), 'style'=>'display:inline-block', 'method'=>'delete')) }}
                <button type="submit" class="icon-bag-btn js-delete-listener" autocomplete="off" title="Удалить сотрудника"></button>
            {{ Form::close() }}
        </div>
        <h3 class="margin-bottom-30">Редактировать анкету сотрудника</h3>

        @include(Helper::acclayout('forms.listener-profile'))
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