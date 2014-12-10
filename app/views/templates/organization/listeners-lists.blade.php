@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<main class="cabinet">
    <?php
    $listeners = User_listener::where('organization_id',Auth::user()->id)->where('active','>=',1)->orderBy('created_at','DESC')->with(array('study'=>function($query){
        $query->orderBy('start_status','DESC');
        $query->orderBy('access_status','DESC');
        $query->orderBy('updated_at','DESC');
        $query->with('order');
        $query->with('course');
        $query->with('final_test');
    }))->get();
    ?>
    <h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <a href="{{ URL::route('signup-listener') }}" class="btn btn--bordered btn--blue pull-right js-btn-add-emp margin-top-20">
            <span class="icon icon-slysh_dob"></span> Добавить
        </a>
        <div class="employees margin-bottom-40">
            <h3 class="margin-bottom-20">Сотрудники</h3>
            <form class="employee-search margin-bottom-20">
                <fieldset>
                    <input type="text" placeholder="Найти сотрудника">
                    <button type="submit"><span class="icon icon-search"></span></button>
                </fieldset>
            </form>
            <table class="tech-table sortable">
                <thead>
                    <tr>
                        <th class="sort listeners-row sort--asc">Ф.И.О. <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                    </tr>
                </thead>
                <tbody>                    
                @foreach($listeners as $listener)
                    @if($listener->study->count())
                        @foreach($listener->study as $index => $study)
                           @include(Helper::acclayout('assets.listener-course-tr'))
                        @endforeach
                    @else
                    <tr>
                        <td class="vertical-top"><a href="{{ URL::route('organization-listener-profile',$listener->id) }}">{{ $listener->fio }}</a></td>
                        <td class="vertical-top"><span class="no-courses">Для этого сотрудника курсы не покупались</span></td>
                        <td class="vertical-top">
                            
                        </td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            <p class="hidden js-search-table-error font-sm text-center margin-top-20">Ничего не найдено</p>
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
{{ HTML::script('js/system/main.js') }}
{{ HTML::script('js/vendor/SmartNotification.min.js') }}
{{ HTML::script('js/system/messages.js') }}
{{ HTML::script('theme/js/organization.js') }}
<script>
    $(function(){
        $(".more-courses").click(function(){
            var index = $(this).attr('data-index');
            $("tr[data-index='"+index+"']:hidden").hide().removeClass('hidden').slideDown(500);
            $(this).hide();

            $("tr[data-index='"+index+"']").last().find("td:nth-child(2)").append('<a href="#" style="display: block;" class="hide-courses">скрыть</a>');
        });

        $(document).on('click', '.hide-courses', function(e){
            e.preventDefault();
            var index = $(this).parents('tr').data('index');
            var trs = $("tr[data-index='"+index+"']").not("tr[data-index='"+index+"']:first").show().slideUp(500).addClass('hidden');
            $(this).hide();
            $("tr[data-index='"+index+"']:first").find('.more-courses').show();
        });

    });
</script>
@stop