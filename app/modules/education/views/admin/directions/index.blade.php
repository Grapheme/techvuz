@extends(Helper::acclayout())
@section('content')
    <h1 class="uppercase"><!-- Направления и курсы:  -->Направления обучения</h1>
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">
    		<div class="pull-left margin-right-10">
    		@if(Allow::action($module['group'], 'create'))
    			<a class="btn btn-primary" href="{{ URL::route('directions.create') }}">Добавить направление</a>
    		@endif
    		</div>
    	</div>
    </div>
    @if($directions->count())
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		<table class="table table-striped table-bordered">
    			<thead>
    				<tr>
    					<th class="col-lg-1 text-center">№ п.п</th>
    					<th class="col-lg-1 text-center">Код</th>
    					<th class="col-lg-6 text-center">Название</th>
    					<th class="col-lg-1 text-center">Скидка, %</th>
    					<th class="col-lg-1 text-center"></th>
    					<th class="col-lg-2 text-center"></th>
    				</tr>
    			</thead>
    			<tbody class="sortable">
    			@foreach($directions as $direction)
    				<tr data-id="{{ $direction->id }}" class="vertical-middle">
    					<td>{{ $direction->order }}</td>
    					<td>{{ $direction->code }}</td>
                        <td>{{ $direction->title }}</td>
                        <td class="text-center">
                        @if($direction->use_discount)
                            {{ $direction->discount }}
                        @else
                            не действует
                        @endif
                        </td>
                        <td class="text-center"><a href="{{ URL::route('courses.index',array('directions'=>$direction->id)) }}" class="btn btn-link margin-right-10">Курсы ({{ $direction->courses->count() }})</a></td>
    					<td class="text-center" style="white-space:nowrap;">
        					@if(Allow::action($module['group'], 'edit'))
        					<a href="{{ URL::route('directions.edit',array('directions'=>$direction->id)) }}" class="btn btn-success margin-right-10">Изменить</a>
                    		@endif
        					@if(Allow::action($module['group'], 'delete'))
							<form method="DELETE" action="{{ URL::route('directions.destroy',array('directions'=>$direction->id)) }}" style="display:inline-block">
								<button type="submit" class="btn btn-danger {{ $direction->courses->count() ? 'dont-remove-direction' : 'remove-direction' }}">
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
    				В данном разделе находятся направления обучения
    				<p><br><i class="regular-color-light fa fa-th-list fa-3x"></i></p>
    			</div>
    		</div>
    	</div>
    </div>
@endif

@stop

@section('scripts')
<script>
var essence = 'direction';
var essence_name = 'направление обучения';
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
                        url: "{{ URL::route('directions.order') }}",
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
