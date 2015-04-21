@extends(Helper::layout())
@section('style')

@stop
@section('content')
<main class="registration">
    @if(!empty($page->seo->h1)) <h1>{{ $page->seo->h1 }}</h1> @endif
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
    <div class="desc">{{ $page->block('seo') }}</div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop