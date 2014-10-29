@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'/menu')

	@if($count = @count($elements))

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pos-rel">
                <table class="table table-striped table-bordered min-table white-bg pos-rel">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:40px">#</th>
                            <th style="width:100%;"class="text-center">{{ $dic->name_title ?: 'Название' }}</th>
                            @if ($actions_column)
                            <th colspan="2" class="width-250 text-center">Действия</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="dicvals @if($sortable) sortable @endif">

                    {{ Helper::d_(Input::all()) }}
                    {{ Helper::d_( Helper::multiArrayToAttributes(Input::get('filter'), 'filter') ) }}

                    @foreach($elements as $e => $element)
                        <? #continue; ?>
                        <tr @if($sortable) data-id="{{ $element->id }}" @endif>
                            <td class="text-center">
                                {{ $e+1 }}
                            </td>
                            <td>
                                <?
                                $line = $element->name;
                                if (isset($dic_settings['first_line_modifier']) && is_callable($dic_settings['first_line_modifier']))
                                    $line = $dic_settings['first_line_modifier']($line, $dic, $element);
                                ?>
                                {{ $line }}
                                <br/>
                                <span class="note dicval_note">
                                <?
                                $line = $element->slug;
                                if (isset($dic_settings['second_line_modifier']) && is_callable($dic_settings['second_line_modifier']))
                                    $line = $dic_settings['second_line_modifier']($line, $dic, $element);
                                ?>
                                {{ $line }}
                                </span>
                            </td>

                            @if ($actions_column)
                            <td class="text-center" style="white-space:nowrap;">

                                @if (NULL != ($actions = @$dic_settings['actions']) && @is_callable($actions))
                                    {{ $actions($dic, $element) }}
                                @endif

                                @if(Allow::action($module['group'], 'dicval_edit'))
                                <a href="{{ action(is_numeric($dic_id) ? 'dicval.edit' : 'entity.edit', array('dic_id' => $dic_id, 'id' => $element->id)) . (Request::getQueryString() ? '?' . Request::getQueryString() : '') }}" class="btn btn-success">
                                    Изменить
                                </a>
                                @endif

                                @if(
                                    Allow::action($module['group'], 'dicval_delete')
                                    && (
                                            !isset($dic_settings['min_elements'])
                                            || ($dic_settings['min_elements'] > 0 && $total_elements > $dic_settings['min_elements'])
                                        )
                                )
                                <form method="POST" action="{{ action(is_numeric($dic_id) ? 'dicval.destroy' : 'entity.destroy', array('dic_id' => $dic_id, 'id' => $element->id)) }}" style="display:inline-block">
                                    <button type="submit" class="btn btn-danger remove-record">
                                        Удалить
                                    </button>
                                </form>
                                @endif

                            </td>
                            @endif

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($dic->pagination > 0)
            {{ $elements->links() }}
        @endif

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

    @if ($sortable)
    <script>
        init_sortable("{{ URL::route('dicval.order') }}", ".dicvals");
    </script>
    @endif

    @if (@trim($dic_settings['javascript']))
    <script>
        {{ @$dic_settings['javascript'] }}
    </script>
    @endif

@stop

