@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'menu')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


            @if (count($templates))
            @foreach($templates as $mod_name => $files)
            <?
            $i = 0;
            ?>
            <h2 style="margin:-10px 0 10px 0">&laquo;{{ @$modules[$mod_name]['title'] }}&raquo;:</h2>
            <table class="table table-striped table-bordered min-table margin-bottom-25">
                <thead>
                <tr>
                    <th class="text-center" style="width:50px">#</th>
                    <th class="text-center" style="width:0px">Шаблон</th>
                    <th class="text-center" style="width:100px">Действия</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($files as $f => $file)
                    <?
                    if ($mod_name == 'layout') {
                        $full_file0 = 'views/templates/'.Config::get('app.template').'/'.$file.'.blade.php';
                        $full_file = app_path($full_file0);
                    } else {
                        $full_file0 = 'modules/'.$mod_name.'/views/'.$file.'.blade.php';
                        $full_file = app_path($full_file0);
                    }
                    $file_exists = file_exists($full_file);
                    $file_writable = is_writable($full_file);
                    $full_file0 = '/app/' . $full_file0;
                    $class = '';
                    if ($file_exists)
                        if ($file_writable)
                            $class = 'success';
                        else
                            $class = 'warning';
                    else
                        $class = 'danger';
                    ?>
                    <tr class="{{ $class }}">
                        <td class="text-center">
                            {{ ++$i }}
                        </td>
                        <td>
                            {{ $file }}
                            <div class="note">
                                {{ $full_file0 }}
                            </div>
                        </td>
						<td class="text-center" style="white-space:nowrap;">

							<a class="btn btn-success" href="{{ URL::action($module['class'].'@getEdit', array('mod' => $mod_name, 'tpl' => $file)) }}">
								Изменить
							</a>

                            @if (0)
							<form method="POST" action="{{ URL::action($module['class'].'@deleteDestroy', array('mod' => $mod_name, 'tpl' => $file)) }}" style="display:inline-block">
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
            @endforeach
            @endif

        </div>
    </div>

@stop


@section('scripts')
@stop

