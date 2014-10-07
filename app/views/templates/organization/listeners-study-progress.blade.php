@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<main class="cabinet">
    <?php
    $listeners = User_listener::where('organization_id',Auth::user()->id)->where('active',1)->with(array('study'=>function($query){
        $query->where('start_status',1);
        $query->where('over_status',0);
        $query->orderBy('start_date','DESC');
        $query->with('order');
        $query->with('course');
    }))->get();

    $hasStudyProgress = FALSE;
    foreach($listeners as $listener):
        if($listener->study->count()):
            $hasStudyProgress = TRUE;
            break;
        endif;
    endforeach;
    ?>
    <h2>{{ User_organization::where('id',Auth::user()->id)->first()->title }}</h2>
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
                            <tr data-index="{{ $listener->id }}" {{ $index >= 1 ? 'class="hidden"' : '' }}>
                                <td>
                                    @if($index == 0)
                                    <a href="{{ URL::route('company-listener-profile',$listener->id) }}">{{ $listener->fio }}</a>
                                    @endif
                                </td>
                                <td>
                                    {{ $study->course->code }}. {{ $study->course->title }}
                                    @if($index == 0 && $listener->study->count() > 1)
                                    <a href="javascript:void(0);" data-index="{{ $listener->id }}" class="more-courses">показать еще {{ $listener->study->count()-1 }} {{ Lang::choice('курс|курса|курсов',$listener->study->count()-1); }}</a>
                                    @endif
                                </td>
                                <td class="td-status-bar">
                                    <div class="ui-progress-bar bar-1 completed-1 clearfix">
                                        <div class="bar-part bar-part-1"></div>
                                        <div class="bar-part bar-part-2"></div>
                                        <div class="bar-part bar-part-3"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        @else
            <div class="banner banner--red">
                <span>На данный момент никто не обучается</span>
            </div>
        @endif
        </div>
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
            $(this).remove();
        });

    });
</script>
@stop