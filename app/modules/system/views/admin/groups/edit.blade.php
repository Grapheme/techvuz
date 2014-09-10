@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'menu')

    {{ Form::model($group, array('url' => action($module['class'].'@postUpdate', array('group_id' => $group->id)), 'class'=>'smart-form', 'id'=>'group-form', 'role'=>'form', 'method'=>'post')) }}
	<div class="row">
		<section class="col col-6">
			<div class="well">
				<header>Доступ группы к модулям:</header>

				<fieldset>
				@if(@count($mod_actions))
					<section>
						<div class="">
    					@foreach($mod_actions as $module_name => $actions)
                        <? #Helper::d($module_name); ?>
                        <? if (!Allow::module($module_name)) { continue; } ?>
                            <? if (!count($actions)) continue; ?>
                            <? $title = isset($mod_info[$module_name]['title']) ? $mod_info[$module_name]['title'] : $module_name; ?>
    						<div class="row margin-bottom-10">
                                <div class="col col-8 margin-bottom-10">
                                    <h3>{{ $title }}</h3>
                                </div>

                                <div class="col col-4">
{{--
                                    <!-- Задумка на будущее: возможность отключать модуль для конкретной группы -->
                                    <!-- 1) Закрыть доступ к действиям модуля 2) Не отображать ссылки модуля в меню
                                    <input type="checkbox"{{ $checked }} class="module-checkbox" data-action="{{ $action }}">
    								<i data-swchon-text="вкл" data-swchoff-text="выкл"></i> 
--}}
                                </div>

            					@foreach($actions as $a => $action)
        							<?php $checked = ''; ?>
        							@if(@$group_actions[$module_name][$a] == 1)
        								<?php $checked = ' checked="checked"'; ?>
        							@endif 

                                    <div class="col col-8">
                                        <label for="act_{{ $module_name }}_{{ $a }}">{{ @$action }}</label>
                                    </div>
                                    <div class="col col-4">
            							<input type="checkbox"{{ $checked }} value="{{ $a }}" name="actions[{{ $module_name }}][]" class="module-checkbox" id="act_{{ $module_name }}_{{ $a }}">
        								<i data-swchon-text="вкл" data-swchoff-text="выкл"></i> 
                                    </div>
            					@endforeach

    						</div>
    					@endforeach
                        </div>
					</section>
				@endif
				</fieldset>

				<footer>
					<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{URL::previous()}}">
						<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
					</a>
					<button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
						<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
					</button>
				</footer>

			</div>
		</section>

		<section class="col col-6">
			<div class="well">
				<header>Данные о группе:</header>
				<fieldset>

					<section>
						<label class="label">Название</label>
						<label class="input">
							{{ Form::text('name', NULL) }}
						</label>
						<div class="note">Только латинские символы, без пробелов. Например: admin</div>
					</section>

					<section>
						<label class="label">Описание</label>
						<label class="input">
							{{ Form::text('desc', NULL) }}
						</label>
						<div class="note">Описание группы. Например: Администраторы</div>
					</section>

					<section>
						<label class="label">Базовый шаблон</label>
						<label class="input">
							{{ Form::text('dashboard', NULL) }}
						</label>
						<div class="note">Наименование используемого базового шаблона. Например: default</div>
					</section>

					<section>
						<label class="label">Стартовая страница</label>
						<label class="input">
							{{ Form::text('start_url', NULL) }}
						</label>
						<div class="note">Страница, на которую попадет пользователь после авторизации. Можно оставить пустым.</div>
					</section>

				</fieldset>

				<footer>
					<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{URL::previous()}}">
						<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
					</a>
					<button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
						<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
					</button>
				</footer>

			</div>
		</section>

	</div>
    {{ Form::close() }}
@stop


@section('scripts')
	<script src="{{ link::to('js/modules/groups.js') }}"></script>
	<script type="text/javascript">
		if(typeof pageSetUp === 'function'){pageSetUp();}
		if(typeof runFormValidation === 'function'){
			loadScript("{{ asset('js/vendor/jquery-form.min.js') }}", runFormValidation);
		}else{
			loadScript("{{ asset('js/vendor/jquery-form.min.js') }}");
		}
	</script>
@stop