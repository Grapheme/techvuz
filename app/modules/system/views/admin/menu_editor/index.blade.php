@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'menu')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


            @if (count($menus))
            <table class="table table-striped table-bordered min-table margin-bottom-25 white-bg">
                <thead>
                <tr>
                    <th class="text-center" style="width:50px">#</th>
                    <th class="text-center" style="width:0px">Меню</th>
                    <th class="text-center" style="width:100px">Действия</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($menus as $m => $menu)
                    <?
                    #Helper::ta($menu);
                    ?>
                    <tr class="">
                        <td class="text-center">
                            {{ ($m+1) }}
                        </td>
                        <td>
                            {{ $menu->title ?: $menu->name }}
                            <div class="note">
                                {{ $menu->title ? $menu->name : '' }}
                            </div>
                        </td>
						<td class="text-center" style="white-space:nowrap;">

							<a class="btn btn-default" href="{{ URL::action($module['name'].'.manage', array($menu->id)) }}">
								Управление
							</a>

							<a class="btn btn-success" href="{{ URL::action($module['name'].'.edit', array($menu->id)) }}">
								Изменить
							</a>

							<form method="POST" action="{{ URL::action($module['name'].'.destroy', array($menu->id)) }}" style="display:inline-block">
								<button type="button" class="btn btn-danger remove-menu">
									Удалить
								</button>
							</form>
						</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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

        </div>
    </div>

@stop


@section('scripts')
    <script>
    var essence = 'menu';
    var essence_name = 'меню';
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

