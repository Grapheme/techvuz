@extends(Helper::acclayout())
@section('content')
    <h4 class="bigger-register">Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
    <h4 class="bigger-register">Специализированная документация.<br> Курс {{ $course->code }}. &laquo;{{ $course->title }}&raquo;</h4>
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">
    		<div class="pull-left margin-right-10">
    		    <a class="btn btn-default" href="{{ URL::route('directions.index') }}">Направления обучения</a>
    		</div>
    		<div class="pull-left margin-right-10">
    		    <a class="btn btn-default" href="{{ URL::route('courses.index',$direction->id) }}">Курсы обучения</a>
    		</div>
    		<div class="pull-left margin-right-10">
    		@if(Allow::action($module['group'],'create'))
    			<a class="btn btn-primary" href="{{ URL::route('metodical.create',array('directions'=>$direction->id,'course'=>$course->id)) }}">Добавить документ</a>
    		@endif
    		</div>
            <div class="btn-group pull-right margin-right-10">
                <a class="btn btn-default" href="{{ URL::route('metodical.index',array('directions'=>$direction->id,'course'=>$course->id)) }}">
                    {{ $course->code }} ({{ $course->metodicals->count() }})
                </a>
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach(Courses::where('direction_id',$direction->id)->orderBy('order')->with('metodicals')->get() as $course_metodical)
                    <li>
                        <a href="{{ URL::route('metodical.index',array('direction'=>$direction->id,'course'=>$course_metodical->id)) }}">{{ $course_metodical->code }} ({{ $course_metodical->metodicals->count() }})</a>
                    </li>
                    @endforeach
                </ul>
            </div>
    	</div>
    </div>
    @if($course->metodicals->count())
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		<table class="table table-striped table-bordered">
    			<thead>
    				<tr>
    					<th class="col-lg-1 text-center">№</th>
    					<th class="col-lg-9 text-center">Название</th>
    					<th class="col-lg-2 text-center"></th>
    				</tr>
    			</thead>
    			<tbody class="sortable">
    			@foreach($course->metodicals as $metodical)
    				<tr data-id="{{ $metodical->id }}" class="vertical-middle">
    					<td class="text-center">{{ $metodical->order }}</td>
    					<td>{{ $metodical->title }}</td>
    					<td class="text-center" style="white-space:nowrap;">
        					@if(Allow::action($module['group'], 'edit'))
                            <a href="{{ URL::route('metodical.edit',array('directions'=>$direction->id,'course'=>$course->id,'metodical'=>$metodical->id)) }}" class="btn btn-success margin-right-10">Изменить</a>
                            @endif
                            @if(Allow::action($module['group'], 'delete'))
                            <form method="DELETE" action="{{ URL::route('metodical.destroy',array('directions'=>$direction->id,'course'=>$course->id,'metodical'=>$metodical->id)) }}" style="display:inline-block">
                                <button type="submit" class="btn btn-danger remove-course-metodical">
                                    Удалить
                                </button>
                            </form>
                            @endif
    					</td>
    				</tr>
    			@endforeach
    			</tbody>
    		</table>
    	</div>
    </div>
    @else
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		<div class="ajax-notifications custom">
    			<div class="alert alert-transparent">
    				<h4>Список пуст</h4>
    				В данном разделе находятся cпециализированная документация для курса
    				<p><br><i class="regular-color-light fa fa-th-list fa-3x"></i></p>
    			</div>
    		</div>
    	</div>
    </div>
@endif

@stop
@section('scripts')
<script>
var essence = 'course-metodical';
var essence_name = 'документ';
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
<script>
    $(document).on("mouseover", ".sortable", function(e){
        if ( !$(this).data('sortable') ) {
            $(this).sortable({
                stop: function() {
                    var pls = $(this).find('tr');
                    var poss = [];
                    $(pls).each(function(i, item) {
                        poss.push($(item).data('id'));
                    });
                    $.ajax({
                        url: "{{ URL::route('metodical.order') }}",
                        type: "post",
                        data: {poss: poss},
                        success: function() {}
                    });
                }
            });
        }
    });
</script>
@stop
