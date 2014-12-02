<tr data-index="{{ $listener->id }}" {{ $index >= 1 ? 'class="hidden"' : '' }}>
    <td>
        @if($index == 0)
        <a href="{{ URL::route('organization-listener-profile',$listener->id) }}">{{ $listener->fio }}</a>
        @endif
    </td>
    <td>
        {{ $study->course->code }}. {{ $study->course->title }}
        @if($index == 0 && $listener->study->count() > 1)
        <br><a href="javascript:void(0);" data-index="{{ $listener->id }}" class="more-courses">показать еще {{ $listener->study->count()-1 }} {{ Lang::choice('курс|курса|курсов',$listener->study->count()-1); }}</a>
        @endif
    </td>
    <td class="td-status-bar">
        <div title="{{ Lang::get('interface.STUDY_PROGRESS.'.getCourseStudyProgress($study)) }}" class="ui-progress-bar bar-1 completed-{{ getCourseStudyProgress($study) }} clearfix">
            <div class="bar-part bar-part-1"></div>
            <div class="bar-part bar-part-2"></div>
            <div class="bar-part bar-part-3"></div>
        </div>
    </td>
</tr>