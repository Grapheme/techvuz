<?
#$create_title = "Редактировать " . $module['entity_name'] . ":";
#$edit_title   = "Добавить " . $module['entity_name'] . ":";
#$create_title = "Изменить страницу:";
#$edit_title   = "Новая страница:";

#$url        = @$element->id ? URL::route($module['entity'].'.update', array('id' => $element->id)) : URL::route($module['entity'].'.store', array());
$url        = action($module['class'].'@postAjaxPagesSaveBlock');
#$method     = @$element->id ? 'PUT' : 'POST';
$method     = 'POST';
#$form_title = @$element->id ? $create_title : $edit_title;
?>

<?
#Helper::ta($element);
?>

{{ Form::model($element, array('url' => $url, 'class' => 'smart-form2', 'id' => 'block-form', 'role' => 'form', 'method' => $method)) }}
@if ($element->id)
<input type="hidden" name="id" value="{{ $element->id }}" />
@endif
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
                Редактировать блок
            </h4>
        </div>
        <div class="modal-body">

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group">
                        <label class="control-label">
                            Название
                        </label>
                        <input type="text" class="form-control" placeholder="Название блока" name="name" value="{{ $element->name }}" required />
                    </div>

                    <fieldset class="row">

                        <section class="col col-lg-6">
                            <label class="control-label">
                                Системное имя
                            </label>
                            {{--
                            <textarea class="form-control" placeholder="Системное имя" rows="2" name="slug">{{ $element->slug }}</textarea>
                            --}}
                            {{ Form::text('slug', null, array('class' => 'form-control')) }}
                        </section>

                        <section class="col col-lg-6">
                            <label class="control-label">
                                Шаблон блока
                            </label>
                            {{ Form::select('template', array('Выберите...')+$templates, null, array('class' => 'form-control')) }}
                        </section>

                    </fieldset>

                </div>
            </div>

            @if (count($locales) > 1)

            <div class="widget-body" style="padding-top:15px">
                <ul id="myTab2" class="nav nav-tabs bordered" role="tablist">
                    <? $i = 0; ?>
                    @foreach ($locales as $locale_sign => $locale_name)
                    <li class="{{ !$i++ ? 'active' : '' }}">
                        <a href="#block_meta_{{ $locale_sign }}" class="modaltablink" data-toggle="tab">
                            {{ $locale_name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div id="myTabContent2" class="tab-content padding-10">
                    <? $i = 0; ?>
                    @foreach ($locales as $locale_sign => $locale_name)
                    <div class="tab-pane fade{{ !$i++ ? ' active in' : '' }}" id="block_meta_{{ $locale_sign }}">

                        @include($module['tpl'].'_block_meta', compact('locale_sign', 'locale_name', 'templates', 'element'))

                    </div>
                    @endforeach
                </div>
            </div>

            @else

                @foreach ($locales as $locale_sign => $locale_name)
                    @include($module['tpl'].'_block_meta', compact('locale_sign', 'locale_name', 'templates', 'element'))
                @endforeach

            @endif

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                Закрыть
            </button>
            <button type="submit" class="btn btn-primary btn-form-submit">
                Сохранить
            </button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
{{ Form::close() }}