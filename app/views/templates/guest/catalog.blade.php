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
            @include(Helper::layout('forms.signup-organization'))
        </div>
        <div id="tabs-2">
            @include(Helper::layout('forms.signup-individual'))
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
@stop