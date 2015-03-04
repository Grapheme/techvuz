<?
/**
 * TITLE: Стандартная страница
 */
    $pages = Page::where('publication',1)->where('version_of',null)->lists('name','slug');
    $pages['news'] = 'Новости';
    $pages['reviews'] = 'Отзывы';
    $courses = Courses::where('active',true)->with('seo')->get();
?>
@extends(Helper::layout())
@section('style')@stop
@section('content')
    <main class="contacts">
        @if(!empty($page->seo->h1)) <h1>{{ $page->seo->h1 }}</h1> @endif
        <div class="desc">
            {{ $page->block('seo') }}
        </div>
        <div>
            <ul>
        @if(count($pages))
            @foreach($pages as $page_link => $page_title)
                <li>
                    <a href="{{ URL::route('page', $page_link) }}" target="_blank">{{ $page_title }}</a>
                </li>
            @endforeach
        @endif
        @if(count($courses))
            @foreach($courses as $course)
                <li>
                    <a href="{{ URL::route('course-page',$course->seo->url) }}" target="_blank">{{ !empty($course->seo->title) ? $course->seo->title : $course->title }}</a>
                </li>
            @endforeach
        @endif
            </ul>
        </div>
    </main>
@stop
@section('scripts')
@stop