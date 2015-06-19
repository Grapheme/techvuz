@extends(Helper::acclayout())
@section('content')
<h4 class="bigger-register">Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
<h4 class="bigger-register">Курс {{ $course->code }}. &laquo;{{ $course->title }}&raquo;</h4>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">
        <div class="pull-left margin-right-10">
            <a class="btn btn-default" href="{{ URL::route('directions.index') }}">Направления обучения</a>
        </div>
        <div class="pull-left margin-right-10">
            <a class="btn btn-default" href="{{ URL::route('courses.index',array('direction'=>$direction->id)) }}">Список курсов</a>
        </div>
        <div class="btn-group pull-right margin-right-10">
            {{ Form::open(array('url'=>URL::route('modules.dublicate',array('directions'=>$direction->id,'course_id'=>$course->id)), 'role'=>'form', 'class'=>'smart-form', 'method'=>'post')) }}
                <button type="submit" autocomplete="off" class="btn btn-success create-dublicate-module">Создать копию в</button>
                {{ Form::select('course_id',Courses::where('id','!=',$course->id)->orderBy('code')->lists('code','id')) }}
            {{ Form::close() }}
        @if(Session::has('message'))
            <?php $message = Session::get('message'); ?>
            @if(!empty($message))
            <div class="alert alert-info fade in">
                <i class="fa-fw fa fa-info"></i> {{ Session::get('message') }}
            </div>
            @endif
        @endif
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
                    <td class="col-lg-10">{{ $chapter->order }}. {{ $chapter->title }}</td>
                    <td class="col-lg-2 text-center">
                        @if(Allow::action($module['group'], 'edit'))
                        <a href="{{ URL::route('chapters.edit',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id)) }}" class="btn btn-success margin-right-10">Изменить</a>
                        @endif
                        @if(Allow::action($module['group'], 'delete'))
                        <form method="DELETE" action="{{ URL::route('chapters.destroy',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id)) }}" style="display:inline-block">
                            <button type="submit" class="btn btn-danger {{ $chapter->lectures->count() ? 'dont-remove-chapter' : 'remove-chapter' }}">
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
            <tbody class="sortable" data-chapter="{{ $chapter->id  }}">
                @foreach($chapter->lectures as $lecture)
                <tr data-id="{{ $lecture->id }}" class="vertical-middle">
                    <td class="col-lg-1 text-center">{{ $chapter->order }}.{{ $lecture->order }}</td>
                    <td class="col-lg-9">{{ $lecture->title }}</td>
                    <td class="col-lg-2 text-center">
                        @if(Allow::action($module['group'], 'edit'))
                        <a href="{{ URL::route('lectures.edit',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id,'lecture'=>$lecture->id)) }}" class="btn btn-success margin-right-10">Изменить</a>
                        @endif
                        @if(Allow::action($module['group'], 'delete'))
                        <form method="DELETE" action="{{ URL::route('lectures.destroy',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id,'lecture'=>$lecture->id)) }}" style="display:inline-block">
                            <button type="submit" target="reload" class="btn btn-danger remove-lecture">Удалить</button>
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
                        <a class="btn btn-success{{ empty($chapter->test) ? ' create-intermediate-test' : '' }}" {{ $chapter->lectures->count() ? '' : 'disabled' }} href="{{ URL::route('testing.index',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id)) }}">{{ empty($chapter->test) ? 'Добавить промежуточный тест' : 'Редактировать промежуточный тест' }}</a>
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
            <a class="btn btn-success{{ empty($course->test) ? ' create-final-test btn-info' : '' }}" {{ $course->chapters->count() ? '' : 'disabled' }} href="{{ URL::route('testing.index',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>0)) }}">{{ empty($course->test) ? 'Добавить итоговый тест' : 'Редактировать итоговый тест' }}</a>
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

    var essence_lecture = 'lecture';
    var essence_lecture_name = 'лекцию';
</script>
<script src="{{ url('theme/js/course.js') }}"></script>
<script type="text/javascript">
    if(typeof pageSetUp === 'function'){pageSetUp();}
    if(typeof runFormValidation === 'function'){
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}", runFormValidation);
    }else{
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}");
    }
</script>
<script>
    $(document).on("mouseover", ".sortable", function(e){
        if ( !$(this).data('sortable') ) {
            $(this).sortable({
                stop: function() {
                    var pls = $(this).find('tr');
                    var poss = [];
                    var chapter = $(this).data('chapter');
                    $(pls).each(function(i, item) {
                        poss.push($(item).data('id'));
                    });
                    $.ajax({
                        url: "{{ URL::route('modules.lectures.order') }}",
                        type: "post",
                        data: {poss: poss, chapter:chapter},
                        success: function() {}
                    });
                }
            });
        }
    });
</script>
@stop
