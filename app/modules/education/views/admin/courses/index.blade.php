@extends(Helper::acclayout())
@section('content')
    <h1>Направления и курсы: Курсы. </h1>
    <h4>Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">
    		<div class="pull-left margin-right-10">
    		    <a class="btn btn-default" href="{{ URL::route('directions.index') }}">Направления обучения</a>
    		</div>
    		<div class="pull-left margin-right-10">
    		@if(Allow::action($module['group'],'create'))
    			<a class="btn btn-primary" href="{{ URL::route('courses.create',array('directions'=>$direction->id)) }}">Добавить курс</a>
    		@endif
    		</div>
            <div class="btn-group pull-right margin-right-10">
                <a class="btn btn-default" href="{{ URL::route('courses.index',array('directions'=>$direction->id)) }}">
                    {{ $direction->title }} ({{ $direction->courses->count() }})
                </a>
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach(Directions::with('courses')->get() as $directions)
                    <li>
                        <a href="{{ URL::route('courses.index',array('directions'=>$directions->id)) }}">{{ $directions->title }} ({{ $directions->courses->count() }})</a>
                    </li>
                    @endforeach
                </ul>
            </div>
    	</div>
    </div>
    @if($courses->count())
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		<table class="table table-striped table-bordered">
    			<thead>
    				<tr>
    					<th class="col-lg-1 text-center" style="white-space:nowrap;">Код</th>
    					<th class="col-lg-6 text-center" style="white-space:nowrap;">Название</th>
    					<th class="col-lg-1 text-center" style="white-space:nowrap;">Цена</th>
    					<th class="col-lg-1 text-center" style="white-space:nowrap;">Часы</th>
    					<th class="col-lg-1 text-center"></th>
    					<th class="col-lg-2 text-center"></th>
    				</tr>
    			</thead>
    			<tbody>
    			@foreach($courses as $course)
    				<tr class="vertical-middle">
    					<td>{{ $course->code }}</td>
    					<td>{{ $course->title }}</td>
    					<td>{{ $course->price }} руб.</td>
    					<td>{{ $course->hours }}</td>
    					<td><a href="{{ URL::route('modules.index',array('direction'=>$direction->id,'course'=>$course->id)) }}" class="btn btn-link margin-right-10">Модули ({{ $course->lectures->count() }})</a></td>
    					<td class="text-center" style="white-space:nowrap;">
        					@if(Allow::action($module['group'], 'edit'))
                            <a href="{{ URL::route('courses.edit',array('directions'=>$direction->id,'course'=>$course->id)) }}" class="btn btn-success margin-right-10">Изменить</a>
                            @endif
                            @if(Allow::action($module['group'], 'delete'))
                            <form method="DELETE" action="{{ URL::route('courses.destroy',array('directions'=>$direction->id,'course'=>$course->id)) }}" style="display:inline-block">
                                <button type="submit" {{ $course->lectures->count() ? 'disabled' : '' }} class="btn btn-danger remove-course">
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
    				В данном разделе находятся курсы
    				<p><br><i class="regular-color-light fa fa-th-list fa-3x"></i></p>
    			</div>
    		</div>
    	</div>
    </div>
@endif

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