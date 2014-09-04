@extends(Helper::acclayout())


@section('content')
    <h1>Информационные блоки: Элементы</h1>

    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">

    		<div class="pull-left margin-right-10">
    		@if(Allow::action('channels', 'channel_create'))
    			<a class="btn btn-primary" href="{{ link::auth($module['rest'].'/create' . ($cat > 0 ? '?cat='.$cat : '') )}}">Новый элемент</a>
    		@endif
    		</div>

            @if($categories->count())
            <div class="btn-group pull-left margin-right-10">
                @if (isset($category) && is_object($category) && $category->id)
                <a class="btn btn-default" href="?cat={{ $category->id }}">
                    {{ $category->title }} ({{ $category->count_channels() }})
                </a>
                @else
                <a class="btn btn-default" href="?">
                    Из всех категорий ({{ Channel::count() }})
                </a>
                @endif
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="?">
                            Из всех категорий ({{ Channel::count() }})
                        </a>
                    </li>
                    <li class="divider"></li>
                    @foreach($categories as $categ)
                    <li>
                        <a href="?cat={{ $categ->id }}">{{ $categ->title }} ({{ $categ->count_channels() }})</a>
                    </li>
                    @endforeach
                </ul>
            </div>
    		@endif

    	</div>
    </div>

    @if($channels->count())
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
    		<table class="table table-striped table-bordered">
    			<thead>
    				<tr>
                        {{--
    					<th class="col-lg-1 text-center">ID</th>
                        --}}
    					<th class="col-lg-10 text-center" style="white-space:nowrap;">Элемент</th>
    					<th class="col-lg-1 text-center">Действия</th>
    				</tr>
    			</thead>
    			<tbody>
    			@foreach($channels as $channel)
    				<tr class="vertical-middle">
                        {{--
    					<td class="text-center">{{ $channel->id }}</td>
                        --}}
    					<td>
                            {{ $channel->title }}
                        </td>
    					<td class="text-center" style="white-space:nowrap;">

        					@if(Allow::action($module['group'], 'channel_edit'))
							<form method="GET" action="{{ link::auth($module['rest'].'/edit/'.$channel->id) }}" style="display:inline-block">
								<button type="submit" class="btn btn-success margin-right-10">
									Изменить
								</button>
							</form>
                    		@endif

        					@if(Allow::action($module['group'], 'channel_delete'))
							<form method="POST" action="{{ link::auth($module['rest'].'/destroy/'.$channel->id) }}" style="display:inline-block">
								<button type="submit" class="btn btn-danger remove-channel">
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

    {{ $channels->appends(array('cat' => $cat))->links() }}

    @else
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		<div class="ajax-notifications custom">
    			<div class="alert alert-transparent">
    				<h4>Список пуст</h4>
    				В данном разделе находятся элементы информационных каналов
    				<p><br><i class="regular-color-light fa fa-th-list fa-3x"></i></p>
    			</div>
    		</div>
    	</div>
    </div>
@endif

@stop


@section('scripts')
    <script>
    var essence = 'channel';
    var essence_name = 'элемент';
	var validation_rules = {
		title: { required: true },
		category_id: { required: true },
		//desc: { required: true },
	};
	var validation_messages = {
		title: { required: 'Укажите название' },
		category_id: { required: 'Укажите категорию' },
		//desc: { required: 'Укажите описание' },
	};
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
