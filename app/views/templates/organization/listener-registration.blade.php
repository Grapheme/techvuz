@extends(Helper::layout())
@section('style')
@stop
@section('content')
<main>
    <div class="edit-employee-anket">
        <h3 class="margin-bottom-30">Добавить сотрудника</h3>
        <form class="registration-form">
            <div class="form-element">
                <label>Ф.И.О.</label><input type="text">
            </div>
            <div class="form-element">
                <label>Должность</label><input type="text">
            </div>
            <div class="form-element">
                <label>Email</label><input type="text">
            </div>
            <div class="form-element">
                <label>Адрес</label><input type="text">
            </div>
            <div class="form-element">
                <label>Телефон</label><input type="text">
            </div>
            <div class="form-element">
                <label>Образование</label><input type="text">
            </div>
            <div class="form-element">
                <label>Место работы</label><input type="text">
            </div>
            <div class="form-element">
                <label>Год обучения</label><input type="text">
            </div>
            <div class="form-element">
                <label>Специальность</label><input type="text">
            </div>
            <div class="form-element">
                <button class="btn btn--bordered btn--blue">
                    Добавить
                </button>
            </div>
        </form>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop