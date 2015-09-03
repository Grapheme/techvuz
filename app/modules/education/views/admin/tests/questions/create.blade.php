@extends(Helper::acclayout())
@section('style')
{{ HTML::style('css/redactor.css') }}
@stop
@section('content')
    <h4 class="bigger-register">Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
    <h4 class="bigger-register">Курс {{ $course->code }} &laquo;{{ $course->title }}&raquo;</h4>
    @if(!is_null($chapter))
        <h4 class="bigger-register">Глава &laquo;{{ $chapter->title }}&raquo;</h4>
        <h4 class="bigger-register">@if(!empty($chapter->test_title)){{ $chapter->test_title }}@else{{ $test->title }}@endif</h4>
        <?php $chapter_id = $chapter->id?>
    @else
        <h4 class="bigger-register">@if(!empty($course->test_title)){{ $course->test_title }}@else{{ $test->title }}@endif</h4>
        <?php $chapter_id = 0; ?>
    @endif
    <h4 class="bigger-register">Добавление вопроса</h4>
{{ Form::open(array('url'=>URL::route('questions.store',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter_id,'test'=>$test->id)), 'role'=>'form', 'class'=>'smart-form', 'id'=>'question-form', 'method'=>'post')) }}
	{{ Form::hidden('test_id',$test->id) }}
	{{ Form::hidden('order',(int) DB::table('tests_questions')->where('test_id',$test->id)->max('order')+1) }}
	{{ Form::hidden('title','Вопрос №') }}
	<div class="row margin-top-10">
		<section class="col col-6">
			<div class="well">
				<fieldset>
                    <section>
						<label class="label">Вопрос</label>
						<label class="textarea">
							{{ Form::textarea('description', '',array('class'=>'redactor')) }}
						</label>
					</section>
                    <section>
                        <label class="label">Комментарий</label>
                        <label class="textarea">
                            {{ Form::textarea('description', '',array('class'=>'redactor')) }}
                        </label>
                    </section>
                </fieldset>
				<footer>
					<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{URL::previous()}}">
						<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
					</a>
					<button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
						<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Создать</span>
					</button>
				</footer>
			</div>
		</section>
	</div>
{{ Form::close() }}
@stop
@section('scripts')
<script>
var essence = 'question';
var essence_name = 'вопрос';
var validation_rules = {
    test_id: { required: true },
    title: { required: true },
};
var validation_messages = {
    test_id: { required: 'Укажите тест' },
    title: { required: 'Укажите название' },
};
</script>
{{ HTML::script('js/modules/standard.js') }}
<script type="text/javascript">
    if(typeof pageSetUp === 'function'){pageSetUp();}
    if(typeof runFormValidation === 'function'){
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}", runFormValidation);
    }else{
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}");
    }
</script>
{{ HTML::script('js/vendor/redactor.js') }}
{{ HTML::script('js/system/redactor-config.js') }}
@stop