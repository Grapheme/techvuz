@extends(Helper::acclayout())
@section('content')
<h1>Направления и курсы: Курсы. </h1>
<h4>Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
@if(!is_null($chapter))
<h4>Глава &laquo;{{ $chapter->title }}&raquo;. {{ $test->title }}</h4>
@else
<h4>{{ $test->title }}</h4>
@endif
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">
        <div class="pull-left margin-right-10">
            <a class="btn btn-default" href="{{ URL::route('directions.index') }}">Направления обучения</a>
        </div>
        <div class="pull-left margin-right-10">
            <a class="btn btn-default" href="{{ URL::route('courses.index',array('direction'=>$direction->id)) }}">Список курсов</a>
        </div>
        <div class="pull-left margin-right-10">
            <a class="btn btn-default" href="{{ URL::route('modules.index',array('direction'=>$direction->id,'course'=>$course->id)) }}">Модули</a>
        </div>
    </div>
</div>
@stop
@section('scripts')
<script>
var essence = 'course';
var essence_name = 'курс';
var validation_rules = {};
var validation_messages = {};
</script>
<script src="{{ url('js/modules/standard.js') }}"></script>
<script type="text/javascript">
    if(typeof pageSetUp === 'function'){pageSetUp();}
    if(typeof runFormValidation === 'function'){
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}", runFormValidation);
    }else{
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}");
    }
</script>
@stop
