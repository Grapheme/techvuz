<tr>
    <td>
    @if($listener_course->access_status == 1)
        <a href="{{ URL::route('listener-study-course',$listener_course->id.'-'.BaseController::stringTranslite($listener_course->course->title,100)) }}">{{ $listener_course->course->code }}. {{ $listener_course->course->title }}</a>
    @else
        {{ $listener_course->course->code }}. {{ $listener_course->course->title }}
    @endif
    </td>
    <td class="td-status-bar">
        <div class="ui-progress-bar bar-1 completed-{{ getCourseStudyProgress($listener_course) }} clearfix">
            <div class="bar-part bar-part-1"></div>
            <div class="bar-part bar-part-2"></div>
            <div class="bar-part bar-part-3"></div>
        </div>
    </td>
</tr>