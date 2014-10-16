<?
$tpl_exists = file_exists($full_file);
$tpl_writable = @is_writable($full_file);
$tpl_content = @file_get_contents($full_file);
?>
@extends(Helper::acclayout())


@section('style')
<style>
#tpl_content {
    width: 100%;
    height: 100%;
    display: block;
}
</style>
@stop


@section('content')


    @include($module['tpl'].'menu')

    @if (!$tpl_exists)
    <div class="alert alert-danger fade in">
        <i class="fa-fw fa fa-warning"></i>
        <strong>Внимание!</strong> Указаный файл не существует. <br/>
        Создайте его вручную и выставьте права 777, или же выберите для редактирования другой файл.
    </div>
    @elseif (!$tpl_writable)
    <div class="alert alert-warning fade in">
        <i class="fa-fw fa fa-warning"></i>
        <strong>Внимание!</strong> Данный файл недоступен для записи, необходимо выставить ему права 777.<br/>
        Для этого подключитесь к серверу по SSH и из корня приложения выполните команду:<br/>
        chmod 777 {{ $file0 }}<br/>
    </div>
    @else

    <div class="row margin-top-10">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            {{ Form::open(array('url' => URL::action($module['class'].'@postSave', array('mod' => $mod_name)), 'class' => 'smart-form2', 'id' => 'tpl-form', 'role' => 'form', 'method' => 'POST')) }}

            {{ Form::hidden('file', $file) }}

            <fieldset class="padding-top-0">
                <button class="btn btn-primary btn-lg submit">
                    <i class="fa fa-save"></i>
                    Сохранить
                </button>

                <section class="smart-form">
                <label class="checkbox">
                    {{ Form::checkbox('git', 1, 1) }}
                    <i></i>
                    Отправить изменения в GIT (возможна небольшая задержка)
                </label>
                </section>

            </fieldset>

            <fieldset class="padding-top-10">
                <section>
                    <label class="textarea" style="display:block; height:auto">
                        <div id="tpl_content">{{ htmlspecialchars($tpl_content) }}</div>
                        {{ Form::textarea('tpl', '', array('class' => 'hidden')) }}
                    </label>
                </section>
            </fieldset>

            {{ Form::close() }}

        </div>
    </div>
    @endif

@stop


@section('scripts')

    @if (0)
    <!-- Create a simple CodeMirror instance -->
    {{ HTML::style('private/js/codemirror/lib/codemirror.css') }}
    {{ HTML::script('private/js/codemirror/lib/codemirror.js') }}
    {{ HTML::script('private/js/codemirror/addon/edit/matchbrackets.js') }}
    {{ HTML::script('private/js/codemirror/mode/htmlmixed/htmlmixed.js') }}
    {{ HTML::script('private/js/codemirror/mode/xml/xml.js') }}
    {{ HTML::script('private/js/codemirror/mode/clike/clike.js') }}
    {{ HTML::script('private/js/codemirror/mode/php/php.js') }}
    <script>
        var myTextarea = document.getElementById("tpl_content");
        var editor = CodeMirror.fromTextArea(myTextarea, {
            lineNumbers: true,
            matchBrackets: true,
            //mode: "text/html",
            mode: "application/x-httpd-php",
            indentUnit: 4,
            indentWithTabs: true,
            lineWrapping: true
        });
    </script>
    @endif

    @if (1)
    <!-- ACE minify -->
    {{ HTML::script('private/js/ace-builds/src-min-noconflict/ace.js') }}
    <script>
        var editor = ace.edit("tpl_content");
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/javascript");
        //editor.getSession().setMode("ace/mode/blade-template");
        editor.setOptions({
            maxLines: Infinity
        });
    </script>
    @endif

    @if (0)
    <!-- ACE full -->
    {{ HTML::script('private/js/ace/demo/kitchen-sink/require.js') }}
    <script>
        // setup paths
        require.config({paths: { "ace" : "{{ asset('private/js/ace/lib/ace') }}" } });
        // load ace and extensions
        require(["ace/ace", "ace/mode/blade-template"], function(ace) {
            var editor = ace.edit("tpl_content");
            editor.setTheme("ace/theme/tomorrow");
            editor.session.setMode("ace/mode/html");
            editor.session.setMode("ace/mode/blade-template");
            editor.setAutoScrollEditorIntoView(true);
            editor.setOption("maxLines", 100);
        });
    </script>
    @endif


    {{ HTML::script("js/vendor/jquery-form.min.js") }}
    <script>
        $(document).on('submit', '#tpl-form', function(e, selector, data) {

            e.preventDefault();
            var form = $(this);
            $('[name=tpl]').val( editor.getValue() );
            console.log(form);
            var options = { target: null, type: $(form).attr('method'), dataType: 'json' };

            options.beforeSubmit = function(formData, jqForm, options){
                $(form).find('button.submit').addClass('loading');
            }

            options.success = function(response, status, xhr, jqForm){
                //console.log(response);
                //$('.success').hide().removeClass('hidden').slideDown();
                //$(form).slideUp();

                if (response.status) {
                    showMessage.constructor('Сохранение', 'Успешно сохранено');
                    showMessage.smallSuccess();

                } else {
                    showMessage.constructor('Ошибка при сохранении', response.responseText);
                    showMessage.smallError();
                }

            }

            options.error = function(xhr, textStatus, errorThrown){
                console.log(xhr);
            }

            options.complete = function(data, textStatus, jqXHR){
                $(form).find('button.submit').removeClass('loading');
            }

            $(form).ajaxSubmit(options);

            return false;
        });
    </script>
@stop

