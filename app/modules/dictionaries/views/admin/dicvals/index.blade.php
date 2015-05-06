@extends(Helper::acclayout())



<?
function write_level($hierarchy, $elements, $dic_id, $dic, $dic_settings, $module, $sortable) {
    global $total_elements;
?>
	@if($count = @count($elements))
        <ol class="dd-list">
        @foreach($hierarchy as $h)
            <?
            #Helper::d($h); #continue;
            #if (!isset($h['id']))
            #    continue;
            $element = $elements[$h['id']];
            $line = $element->name;
            if (isset($dic_settings['first_line_modifier']) && is_callable($dic_settings['first_line_modifier']))
                $line = $dic_settings['first_line_modifier']($line, $dic, $element);

            $line = preg_replace("~<br[/ ]*?".">~is", ' ', $line);

            $line2 = $element->slug;
            if (isset($dic_settings['second_line_modifier']) && is_callable($dic_settings['second_line_modifier']))
                $line2 = $dic_settings['second_line_modifier']($line2, $dic, $element);
            $line2 = preg_replace("~<br[/ ]*?".">~is", ' ', $line2);
            ?>

            <li class="dd-item dd3-item dd-item-fixed-height" data-id="{{ $element->id }}">
                @if ($sortable > 0)
                <div class="dd-handle dd3-handle">
                    Drag
                </div>
                @endif
                <div class="dd3-content{{ $sortable > 0 ? '' : ' padding-left-15 padding-top-10' }} clearfix">

                    @if (@$actions_column || 1)

                        <div class="pull-right dicval-actions dicval-main-actions dicval-actions-margin-left">
                            @if(Allow::action($module['group'], 'dicval_edit'))
                            <a href="{{ action(is_numeric($dic_id) ? 'dicval.edit' : 'entity.edit', array('dic_id' => $dic_id, 'id' => $element->id)) . (Request::getQueryString() ? '?' . Request::getQueryString() : '') }}" class="btn btn-success dicval-action dicval-actions-edit" title="Изменить">
                                <!--Изменить-->
                            </a>
                            @endif

                            @if(
                                Allow::action($module['group'], 'dicval_delete')
                                && (
                                        !isset($dic_settings['min_elements'])
                                        || ($dic_settings['min_elements'] > 0 && $total_elements > $dic_settings['min_elements'])
                                    )
                            )
                            <form method="POST" action="{{ action(is_numeric($dic_id) ? 'dicval.destroy' : 'entity.destroy', array('dic_id' => $dic_id, 'id' => $element->id)) }}" style="display:inline-block" class="dicval-action dicval-actions-delete">
                                <button type="button" class="btn btn-danger remove-dicval-list" title="Удалить">
                                    <!--Удалить-->
                                </button>
                            </form>
                            @endif
                        </div>

                        <div class="pull-right dicval-actions">
                            @if (NULL != ($actions = @$dic_settings['actions']) && @is_callable($actions))
                                {{ $actions($dic, $element) }}
                            @endif
                        </div>

                    @endif

                    <div class="dicval-lines">
                        {{ $line }}
                        <br/>
                        <span class="note dicval_note">
                            {{ $line2 }}
                        </span>
                    </div>


                </div>
                @if (isset($h['children']) && is_array($h['children']) && count($h['children']))
                    <?
                    /**
                     * Вывод дочерних элементов
                     */
                    write_level($h['children'], $elements, $dic_id, $dic, $dic_settings, $module, $sortable);
                    #Helper::dd($h['children']);
                    ?>
                @endif
            </li>
        @endforeach

        </ol>

    @endif
<?
}
?>




@section('content')

    @include($module['tpl'].'/menu')


	@if($count = @count($elements))

        <div class="dd dicval-list" data-output="#nestable-output">
            <?
            write_level($hierarchy, $elements, $dic_id, $dic, $dic_settings, $module, $sortable);
            ?>
        </div>

        <div class="clear"></div>

        @if ($dic->pagination > 0)
            {{ $elements_pagination->links() }}
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

    <div class="clear"></div>

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

	<script type="text/javascript">
		if(typeof pageSetUp === 'function'){pageSetUp();}
		if(typeof runDicValFormValidation === 'function'){
			loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}", runDicValFormValidation);
		}else{
			loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}");
		}
	</script>

    @if (@trim($dic_settings['javascript']))
    <script>
        {{ @$dic_settings['javascript'] }}
    </script>
    @endif

    @if ($sortable && $dic->sortable > 0)
    <script>
    $(document).ready(function() {

        var updateOutput = function(e) {

            show_hide_delete_buttons();

            var list = e.length ? e : $(e.target), output = $(list.data('output'));
            if (window.JSON) {
                var data = window.JSON.stringify(list.nestable('serialize'));
                $.ajax({
                    url: "{{ URL::route('dicval.nestedsetmodel') }}",
                    type: "post",
                    data: { data: data },
                    success: function(jhr) {
                        //console.clear();
                        //console.log(jhr);
                    }
                });
                output.val(data);
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };

        //updateOutput($('.dd.dicval-list').data('output', $('#nestable-output')));

        $('.dd.dicval-list').nestable({
            maxDepth: {{ (int)$dic->sortable }},
            group: 1
        }).on('change', updateOutput);

        function show_hide_delete_buttons() {
            $('.dd-item > button:first-child').parent().find('.dd3-content:first .dicval-actions .dicval-actions-delete').hide();
            $('.dd-item > div:first-child').parent().find('.dd3-content:first .dicval-actions .dicval-actions-delete').show();
        }

        show_hide_delete_buttons();

    });
    </script>
    @endif

@stop

