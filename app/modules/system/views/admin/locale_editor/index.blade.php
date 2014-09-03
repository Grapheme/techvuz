@extends(Helper::acclayout())


@section('content')

    <h1>Редактор языковых файлов</h1>

    <a class="btn btn-success margin-right-10" href="{{ URL::action($module['class'].'@getList') }}">
        Показать матрицу
    </a>

    <div class="row margin-top-10">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <p>Языковые версии из конфигурации:</p>

            @if (count($locales))
            <table class="table table-striped table-bordered min-table">
                <thead>
                <tr>
                    <th class="text-center" style="width:0px">#</th>
                    <th style="width:100%;"class="text-center">Название</th>
                    <th class="width-250 text-center">Действия</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($locales as $locale_sign => $locale_name)
                    <tr>
                        <td>
                            {{ $locale_sign }}
                        </td>
                        <td>
                            {{ $locale_name }}
                        </td>
                        <td class="text-center" style="white-space:nowrap;">
                            {{--
                            <a class="btn btn-success margin-right-10" href="{{ URL::action($module['class'].'@getEdit', $locale_sign) }}">
                                Изменить
                            </a>
                            --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

        </div>
    </div>

@stop


@section('scripts')
@stop

