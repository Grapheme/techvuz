@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'/menu')

	@if($count = @count($elements))
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<table class="table table-striped table-bordered min-table white-bg">
				<thead>
					<tr>
						<th class="text-center" style="width:40px">#</th>
						<th style="width:100%;"class="text-center">Название</th>
						<th colspan="2" class="width-250 text-center">Действия</th>
					</tr>
				</thead>
				<tbody class="sortable">
				@foreach($elements as $e => $element)
					<tr data-id="{{ $element->id }}"{{ $element->entity ? ' class="warning"' : '' }}>
						<td class="text-center">
						    {{ $e+1 }}
						</td>
						<td>
                            {{ $element->entity ? '<a href="' . URL::route('entity.index', $element->slug) . '" title="Вынесено в отдельную сущность">' . $element->name . '</a>' : $element->name }}
                            <br/>
                            <span class="note dic_note">
                                {{  $element->slug }}
                            </span>
						</td>
						<td class="text-center" style="white-space:nowrap;">

        					@if(Allow::action($module['group'], 'dicval_view'))
                            <a href="{{ action('dicval.index', array('dic_id' => $element->id)) }}" class="btn btn-warning">
                                Содержимое ({{ $element->count }})
                            </a>
                    		@endif

        					@if(Allow::action($module['group'], 'edit'))
                            <a href="{{ action('dic.edit', array('id' => $element->id)) }}" class="btn btn-success">
                                Изменить
                            </a>
                    		@endif

        					@if(Allow::action($module['group'], 'delete'))
							<form method="POST" action="{{ action('dic.destroy', array('id' => $element->id)) }}" style="display:inline-block">
								<button type="submit" class="btn btn-danger remove-record">
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

    {{-- $elements->links() --}}

	@else
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="ajax-notifications custom">
				<div class="alert alert-transparent">
					<h4>Список пуст</h4>
					<p><br><i class="regular-color-light fa fa-th-list fa-3x"></i></p>
				</div>
			</div>
		</div>
	</div>
	@endif
@stop


@section('scripts')
    <script>
    var essence = 'record';
    var essence_name = 'запись';
	var validation_rules = {
		name: { required: true }
	};
	var validation_messages = {
		name: { required: 'Укажите название' }
	};
    </script>

	{{ HTML::script('js/modules/standard.js') }}

	<script type="text/javascript">
		if(typeof pageSetUp === 'function'){pageSetUp();}
		if(typeof runFormValidation === 'function'){
			loadScript("{{ asset('js/vendor/jquery-form.min.js'); }}", runFormValidation);
		}else{
			loadScript("{{ asset('js/vendor/jquery-form.min.js'); }}");
		}
	</script>

    <script>
        $(document).on("mouseover", ".sortable", function(e){
            // Check flag of sortable activated
            if ( !$(this).data('sortable') ) {
                // Activate sortable, if flag is not initialized
                $(this).sortable({
                    // On finish of sorting
                    stop: function() {
                        // Find all playlists
                        var pls = $(this).find('tr');
                        var poss = [];
                        // Make array with current sorting order
                        $(pls).each(function(i, item) {
                            poss.push($(item).data('id'));
                        });
                        console.log(poss);
                        // Send ajax request to server for saving sorting order
                        $.ajax({
                            url: "{{ URL::route('dic.order') }}",
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

