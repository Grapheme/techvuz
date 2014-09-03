@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'/menu')

	@if($pages->count())
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<table class="table table-striped table-bordered min-table">
				<thead>
					<tr>
                        <th class="text-center" style="width:40px">#</th>
                        <th style="width:100%;"class="text-center">Название</th>
                        <th class="width-250 text-center">Действия</th>
					</tr>
				</thead>
				<tbody>
				@foreach($pages as $p => $page)
					<tr class="{{ $page->start_page ? 'success' : '' }}">
                        <td>
                            {{ $p+1 }}
                        </td>
                        <td>
                            {{ $page->name }}
                            @if ($page->slug)
                                <br/>
                                <span style="color:#999">{{ $page->slug }}</span>
                            @endif
                        </td>

						<td class="text-center" style="white-space:nowrap;">
	    					@if(Allow::action('pages','edit'))
							<a class="btn btn-success margin-right-10" href="{{ URL::route($module['entity'].'.edit', array('page_id' => $page->id)) }}">
								Изменить
							</a>
                            @endif
                            @if(Allow::action('pages','delete'))
							<form method="POST" action="{{ URL::route($module['entity'].'.destroy', array('page_id' => $page->id)) }}" style="display:inline-block">
								<button type="button" class="btn btn-danger remove-page">
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
				</div>
			</div>
		</div>
	</div>
	@endif
@stop


@section('scripts')
	<script src="{{ link::to('js/modules/pages.js') }}"></script>
	<script type="text/javascript">
		if(typeof pageSetUp === 'function'){pageSetUp();}
		if(typeof runFormValidation === 'function'){
			loadScript("{{asset('js/vendor/jquery-form.min.js');}}",runFormValidation);
		}else{
			loadScript("{{asset('js/vendor/jquery-form.min.js');}}");
		}
	</script>
@stop
