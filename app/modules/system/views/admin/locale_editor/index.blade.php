@extends(Helper::acclayout())


@section('content')

    <h1>Редактор языковых файлов</h1>

    <a class="btn btn-success margin-right-10" href="{{ URL::action($module['class'].'@getList') }}">
        Показать матрицу
    </a>

    <div class="row margin-top-10">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <p>Языковые версии из конфигурации:</p>

            @if (count($all_locales))
            <table class="table table-striped table-bordered min-table">
                <thead>
                <tr>
                    <th class="text-center" style="width:0px">#</th>
                    <th style="width:50%;" class="text-center">Конфигурация</th>
                    <th style="width:50%;" class="text-center">Наличие директории</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($all_locales as $locale_sign)
                    <tr class="text-center">
                        <td>
                            {{ $locale_sign }}
                        </td>
                        <td class="{{ @$locales[$locale_sign] ? 'success' : 'danger' }}">
                            {{ @$locales[$locale_sign] }}
                        </td>
                        <td class="{{ @in_array($locale_sign, $dirs) ? 'success' : 'danger' }}">

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

