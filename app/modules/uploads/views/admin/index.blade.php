@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'/menu')

	@if($count = @count($elements))
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<table class="table table-striped2 table-bordered min-table">
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
						<td style="position:relative">

                            @if ($element->module)
                                <strong>{{ $element->module }}{{ $element->unit_id ? ' / '.$element->unit_id : '' }} /</strong>
                            @endif

                            {{ $element->original_name }}

                            <br/>
                            <span class="note">
                                {{ $element->path }}
                            </span>
                            <div class="note">
                                {{ Helper::smartFilesize($element->filesize) }}
                            </div>

                            @if ($element->mime1 == 'image')
                            <div style="background:url({{ $element->filesize < 100000 ? $element->path : '' }}) no-repeat #aaa 50% 50% / cover; position:absolute; top:0; right:0; height:100%; width:100px;">
                                <a href="{{ URL::to($element->path) }}" target="_blank" style="display:block; width:100%; height:100%; color:#fff; text-align:center; margin:12px auto;">@if($element->filesize > 100000) <i class="fa fa-image fa-4x"></i> @endif</a>
                            </div>
                            @endif

						</td>
						<td class="text-center" style="vertical-align:middle; white-space:nowrap;">

                            <a href="{{ URL::to($element->path) }}" target="_blank" download class="btn btn-success margin-right-10">
                                <i class="fa fa-download"></i> Скачать
                            </a>

        					@if(Allow::action($module['group'], 'delete'))
							<form method="POST" action="{{ action('uploads.destroy', array('id' => $element->id)) }}" style="display:inline-block">
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

    {{ $elements->links() }}

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

@stop

