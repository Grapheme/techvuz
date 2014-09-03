@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'menu')

	@if($news->count())
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
				@foreach($news as $n => $new)
					<tr>
						<td class="text-center">
                            {{ $n+1 }}
                        </td>
						<td>
						    <a href="{{ URL::to('news/'.$new->slug) }}" target="_blank">{{ is_object($new->meta) ? $new->meta->title : $new->slug }}</a><br/>
                            <span class="note">{{ date("d.m.Y", strtotime($new->published_at)) }}</span>
						</td>
						<td class="text-center" style="white-space:nowrap;">
                            @if(Allow::action($module['group'], 'edit'))
                            <a class="btn btn-success margin-right-10" href="{{ URL::route($module['entity'].'.edit', array('news_id' => $new->id)) }}">
                                Изменить
                            </a>
                            @endif
                            @if(Allow::action($module['group'], 'delete'))
                            <form method="POST" action="{{ URL::route($module['entity'].'.destroy', array('news_id' => $new->id)) }}" style="display:inline-block">
                                <button type="button" class="btn btn-danger remove-{{ $module['entity'] }}">
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

    {{ $news->links() }}

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
	<script src="{{ url('js/modules/news.js') }}"></script>
	<script type="text/javascript">
		if(typeof pageSetUp === 'function'){pageSetUp();}
		if(typeof runFormValidation === 'function'){
			loadScript("{{ asset('js/vendor/jquery-form.min.js'); }}", runFormValidation);
		}else{
			loadScript("{{ asset('js/vendor/jquery-form.min.js'); }}");
		}
	</script>
@stop

