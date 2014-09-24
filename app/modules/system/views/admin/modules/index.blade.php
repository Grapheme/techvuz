@extends(Helper::acclayout())


@section('content')
<div class="row">

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">

        <h1>Модули</h1>

		<form action="{{ URL::route('modules.change') }}" class="smart-form">
                {{--<label class="label">Список доступных модулей:</label>--}}

                <table class="table table-bordered table-striped white-bg">
                    <?
                    #Helper::d(SystemModules::getModules());
                    $modules_info = Config::get('mod_info');
                    $modules = Allow::modules();
                    #Helper::dd($modules);
                    ?>
                    <tbody class="sortable">
                        @foreach($modules as $name => $module)
                        <tr data-id="{{ @$module->id }}">
                            <td>{{ @$modules_info[$module->name]['title'] }}</td>
                            <td style="width: 50px;">
                                <label class="toggle">
                                    <?php $checked = ''; ?>
                                    @if(Allow::module($module->name))
                                        <?php $checked = ' checked="checked"'; ?>
                                    @endif
                                    <input type="checkbox"{{ $checked }} class="module-checkbox" data-name="{{ $module->name }}">
                                    <i data-swchon-text="вкл" data-swchoff-text="выкл"></i>
                                </label>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
				</table>

		</form>
	</div>

</div>
@stop


@section('scripts')

	{{ HTML::script('js/modules/settings.js') }}

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
                            url: "{{ URL::route('modules.order') }}",
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

