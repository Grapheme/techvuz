@extends('templates.'.AuthAccount::getStartPage())


@section('content')
    <h1>Галерея #{{ $gallery->gallery_id }}: &laquo;{{ $gallery->info()->name }}&raquo;</h1>

   {{ ExtForm::gallery('gallery', @$gallery) }}
@stop


@section('scripts')
    <script>
    //loadScript("{{ asset('js/modules/gallery.js') }}");
    </script>
@stop