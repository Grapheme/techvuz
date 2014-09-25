@extends(Helper::layout())
@section('style')

@stop
@section('content')
<main class="registration">
    {{ $page->block('top_h2') }}
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    @if(Auth::guest())
    <div class="tabs">
        <ul>
            <li><a href="#tabs-1">Юридическое лицо</a></li>
            <li><a href="#tabs-2">Физическое лицо</a></li>
        </ul>
        <div id="tabs-1">
            @include('accounts.views.registration.signup-organization')
        </div>
        <div id="tabs-2">
            @include('accounts.views.registration.signup-individual')
        </div>
    </div>
    @else
        <p>Авторизованные пользователи не могут оформлять заявки на регистрацию</p>
    @endif
</main>
@stop
@section('overlays')
@stop
@section('scripts')
{{ HTML::script('js/vendor/jquery-form.min.js') }}
{{ HTML::script('js/vendor/jquery.validate.min.js') }}
{{ HTML::script('js/system/main.js') }}
{{ HTML::script('theme/scripts/registration.js') }}
<script type="text/javascript">runFormValidation();</script>
{{ HTML::script('js/vendor/jquery.mask.js') }}
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
@stop