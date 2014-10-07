@extends(Helper::layout())
@section('style')
@stop
@section('content')
<main class="catalog">
    {{ $page->block('top_h2') }}
    <div class="print-link">
        <a href="#">Распечатать каталог</a> <span class="icon icon-print"></span>
    </div>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <div class="banner banner--red">
        <span>В августе месяце скидка 30%<br>на курсы по охране труда.</span>
    </div>
    <div class="accordion">
    @foreach(Directions::with('photo')->with('courses')->get() as $direction)
        <div class="accordion-header">
        @if(!empty($direction->photo->name))
            <div class="accordion-img" style="background-image: url('{{ Config::get('site.galleries_photo_public_dir').'/'.$direction->photo->name }}');"></div>
        @endif
            <h3>{{ $direction->title }}</h3>
            <div class="acc-courses">
                {{ $direction->courses->count() }} {{ Lang::choice('курс|курса|курсов',$direction->courses->count()); }}
            </div>
        </div>
        @if($direction->courses->count())
        <div class="accordion-body">
            <table>
                <tr>
                    <th>Название</th>
                    <th>Код</th>
                    <th>Часы</th>
                    <th>Цена</th>
                </tr>
            @foreach($direction->courses as $course)
                <tr>
                    <td>{{ $course->title }}</td>
                    <td><span class="code">{{ $course->code }}</span></td>
                    <td><span class="code">{{ $course->hours }}</span></td>
                    <td><span class="price">{{ number_format($course->price,0,'.',' ')  }}.–</span></td>
                </tr>
            @endforeach
            </table>
        </div>
        @endif
    @endforeach
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop