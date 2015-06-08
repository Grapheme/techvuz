@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<main class="cabinet">
    <?php
    $listeners = User_listener::where('organization_id',Auth::user()->id)->where('active','>=',1)->with(array('study'=>function($query){
        $query->where('start_status',1);
        $query->where('over_status',0);
        $query->orderBy('start_date','DESC');
        $query->with('order');
        $query->with('course');
        $query->with('final_test');
    }))->get();

    $hasStudyProgress = FALSE;
    foreach($listeners as $listener):
        if($listener->study->count()):
            $hasStudyProgress = TRUE;
            break;
        endif;
    endforeach;
    ?>
    <a class="name-dashboard" href="{{ URL::route('dashboard') }}"><h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2></a>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employees margin-bottom-40">
            <h3 class="margin-bottom-20">Ход обучения</h3>
        @if($hasStudyProgress)
            <table class="tech-table sortable">
                <tbody>
                    <tr>
                        <th class="sort sort--asc">Ф.И.О. <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                    </tr>
                @foreach($listeners as $listener)

                    @if($listener->study->count())
                        @foreach($listener->study as $index => $study)
                            @include(Helper::acclayout('assets.listener-course-tr'))
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        @else
            <p>
                На данный момент никто не обучается
            </p>
        @endif
        </div>
        <?php
        $listeners = User_listener::where('organization_id',Auth::user()->id)->where('active','>=',1)->with(array('study'=>function($query){
            $query->where('start_status',1);
            $query->where('over_status',1);
            $query->orderBy('start_date','DESC');
            $query->with('order');
            $query->with('course');
            $query->with('final_test');
        }))->get();

        $hasStudyProgress = FALSE;
        foreach($listeners as $listener):
            if($listener->study->count()):
                $hasStudyProgress = TRUE;
                break;
            endif;
        endforeach;
        ?>
        @if($hasStudyProgress)
        <div class="employees margin-bottom-40">
            <h3 class="margin-bottom-20">Обучение завершено</h3>
            <table class="tech-table sortable">
                <tbody>
                    <tr>
                        <th class="sort sort--asc">Ф.И.О. <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                    </tr>
                @foreach($listeners as $listener)

                    @if($listener->study->count())
                        @foreach($listener->study as $index => $study)
                            @include(Helper::acclayout('assets.listener-course-tr'))
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
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