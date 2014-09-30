<?php
    $news_list = News::with('meta.seo')->limit(12)->get();
?>
@if($news_list->count())
<section class="news">
    <h3>Новости</h3>
    <ul class="news-ul clearfix">
    @foreach($news_list as $news)
        <li class="news-li">
            <div class="news-date">
                {{ Helper::rdate("j M Y", strtotime($news->published_at)) }}
            </div>
            <div class="news-text">
                {{ strip_tags($news->meta->preview) }}
            </div>
   @endforeach
   </ul>
</section>
@endif