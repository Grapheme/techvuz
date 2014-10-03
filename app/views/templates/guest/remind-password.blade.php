@extends(Helper::layout())
@section('style')
@stop
@section('content')
<main>
    @if(Auth::guest())
        @include(Helper::layout('forms.reset-password'))
    @else
        <p>Авторизованные пользователи не могут сбрасывать пароль</p>
    @endif
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop