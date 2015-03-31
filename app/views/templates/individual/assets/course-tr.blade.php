<tr>
    <td>
        @if($listener_course->access_status == 1 && $listener_course->over_status == 0)
            <a href="{{ URL::route('individual-study-course',$listener_course->id.'-'.BaseController::stringTranslite($listener_course->course->title,100)) }}">{{ $listener_course->course->code }}. {{ $listener_course->course->title }}</a>
        @else
            {{ $listener_course->course->code }}. {{ $listener_course->course->title }}
        @endif
    </td>
    <td class="td-status-bar">
        @if(isset($showResults) && $showResults)
            <a class="style-normal nowrap" href="{{ URL::route('individual-order-result-certification',array('order_id'=>$listener_course->order_id,'course_id'=>$listener_course->id,'format'=>'pdf')) }}">
                <span class="icon icon-sertifikat"></span> Результат итоговой аттестации (pdf)
            </a>
        @else
        <div title="{{ Lang::get('interface.STUDY_PROGRESS_LISTENER.'.getCourseStudyProgress($listener_course)) }}" class="ui-progress-bar bar-1 completed-{{ getCourseStudyProgress($listener_course) }} clearfix">
            <div class="bar-part bar-part-1"></div>
            <div class="bar-part bar-part-2"></div>
            <div class="bar-part bar-part-3"></div>
        </div>
        @endif
    </td>
</tr>