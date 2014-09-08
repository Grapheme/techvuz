@extends(Helper::acclayout())
@section('content')
<h1>Направления и курсы: Модули. </h1>
<h4>Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
<h4>Курс &laquo;{{ $course->title }}&raquo;</h4>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">
        <div class="pull-left margin-right-10">
            <a class="btn btn-default" href="{{ URL::route('directions.index') }}">Направления обучения</a>
        </div>
        <div class="pull-left margin-right-10">
            <a class="btn btn-default" href="{{ URL::route('courses.index',array('direction'=>$direction->id)) }}">Список курсов</a>
        </div>
    </div>
</div>
<div class="row">
@if($course->chapters->count())
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    @foreach($course->chapters as $chapter)
        <table class="table table-striped table-bordered">
            <tbody>
                <tr class="vertical-middle">
                    <td class="col-lg-10">{{ $chapter->order }} {{ $chapter->title }}</td>
                    <td class="col-lg-2 text-center">
                        @if(Allow::action($module['group'], 'edit'))
                        <a href="{{ URL::route('chapters.edit',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id)) }}" class="btn btn-success margin-right-10">Изменить</a>
                        @endif
                        @if(Allow::action($module['group'], 'delete'))
                        <form method="DELETE" action="{{ URL::route('chapters.destroy',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id)) }}" style="display:inline-block">
                            <button type="submit" {{ $chapter->lectures->count() ? 'disabled' : '' }} class="btn btn-danger remove-chapter">
                                Удалить
                            </button>
                        </form>
                        @endif
                    </td>
                <tr>
            </tbody>
        </table>
        @if($chapter->lectures->count())
        <table class="table table-striped table-bordered">
            <tbody>
                @foreach($chapter->lectures as $lecture)
                <tr class="vertical-middle">
                    <td class="col-lg-1 text-center">{{ $lecture->order }}</td>
                    <td class="col-lg-9">{{ $course->title }}</td>
                    <td class="col-lg-2 text-center" style="white-space:nowrap;">
                        @if(Allow::action($module['group'], 'edit'))
                        <a href="{{ URL::route('lectures.edit',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id,'lecture'=>$lecture->id)) }}" class="btn btn-success margin-right-10">Изменить</a>
                        @endif
                        @if(Allow::action($module['group'], 'delete'))
                        <form method="DELETE" action="{{ URL::route('lectures.destroy',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id,'lecture'=>$lecture->id)) }}" style="display:inline-block">
                            <button type="submit" class="btn btn-danger remove-lecture">
                                Удалить
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        <table class="table table-striped table-bordered">
            <tbody>
                <tr class="vertical-middle">
                    <td class="col-lg-10">
                    @if(Allow::action($module['group'], 'edit'))
                        <a class="btn btn-success" href="{{ URL::route('lectures.create',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id)) }}">Добавить лекцию</a>
                        <a class="btn btn-info" {{ $course->chapters->count() ? '' : 'disabled' }} href="{{ URL::route('directions.index') }}">Промежуточное тестирование</a>
                    @endif
                    </td>
                    <td class="col-lg-2 text-center"> </td>
                <tr>
            </tbody>
        </table>
    @endforeach
    </div>
@endif
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">
        <div class="pull-left margin-right-10">
            <a class="btn btn-success" href="{{ URL::route('chapters.create',array('directions'=>$direction->id,'course'=>$course->id)) }}">Новая глава</a>
            <a class="btn btn-info" {{ $course->chapters->count() ? '' : 'disabled' }} href="{{ URL::route('directions.index') }}">Итоговое тестирование</a>
        </div>
    </div>
</div>
@stop
@section('scripts')
<script>
    var essence = 'chapter';
    var essence_name = 'главу';
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
