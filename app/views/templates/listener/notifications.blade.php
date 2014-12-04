@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h1>{{ User_listener::where('id',Auth::user()->id)->pluck('fio') }}</h1>
    <!-- Сюда нужно вывести название организации -->
    <p class="style-light style-italic">ООО «Организация»</p>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employees margin-bottom-40">
            <h3 class="margin-bottom-20">Уведомления</h3>
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop