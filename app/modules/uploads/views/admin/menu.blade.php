<?
    $menus = array();
    $menus[] = array(
        'link' => URL::route('uploads.index', null),
        'title' => 'Загрузки',
        'class' => 'btn btn-default'
    );
    if (Allow::action($module['group'], 'view_all')) {
        $menus[] = array(
            'link' => URL::route('uploads.index', array('view' => 'all')),
            'title' => 'Все загрузки',
            'class' => 'btn btn-default'
        );
    }
?>
    
    <h1>Загруженные файлы</h1>

    {{ Helper::drawmenu($menus) }}

    @if (@is_dir(Config::get('site.uploads_dir')) && @is_writable(Config::get('site.uploads_dir')))
        {{ Form::open(array('url' => '#', 'class' => 'smart-form', 'files' => true)) }}
            <section>
                <label class="input pull-left" style="width:300px;">
                    {{ ExtForm::upload('file') }}
                </label>
                &nbsp; <button class="btn btn-default">Загрузить</button>
            </section>
        {{ Form::close() }}
    @else
        <div class="alert alert-danger fade in min-table">
            <i class="fa-fw fa fa-times"></i>
            Директория для загрузки файлов недоступна для записи.<br/>
            Для выставления прав выполните команду в консоли: chmod -R 777 public/uploads
        </div>
    @endif