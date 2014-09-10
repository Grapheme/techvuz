<?php
if (Input::has('query')):
    $result = SphinxsearchController::search(Input::get('query'));
    $totalCount = (int) count($result['channels']) + (int) count($result['products']) + (int) count($result['reviews']) + (int) count($result['pages']);
endif;
?>

<div class="wrapper">
    <div class="us-block">
    @if($totalCount > 0)
        <div class="us-title">Результаты поиска <span class="search-am">({{ $totalCount }})</span></div>
    @else
        <div class="us-title">Ничего не найдено</div>
    @endif
    @if($totalCount > 0)
        <ol class="num-list search-list">
            @if(!is_null($result['channels']) && $result['channels']->count())
                @foreach($result['channels'] as $channel)
            <li>
                <div class="search-text">
                    {{ $channel->title }}. {{ Str::words(strip_tags($channel->short), 100, ' ...') }}
                </div>
                @if(!empty($channel->link))
                <a href="{{ link::to($channel->link) }}" class="post-link">Подробнее</a>
                @endif
                @endforeach
            @endif
            @if(!is_null($result['products']) && count($result['products']))
                @foreach($result['products'] as $product)
            <li>
                <div class="search-text">
                    {{ $product['title'] }}. {{ Str::words(strip_tags($product['short']), 100, ' ...') }}
                </div>
                <a href="{{ link::to('catalog') }}" class="post-link">Подробнее</a>
                {{--
                @if(!empty($product['link']))
                <a href="{{ $product['link'] }}" class="post-link">Подробнее</a>
                @endif
                 --}}
                @endforeach
            @endif
            @if(!is_null($result['reviews']) && $result['reviews']->count())
                @foreach($result['reviews'] as $review)
            <li>
                <div class="search-text">
                    {{ $review->meta->first()->name }}. {{ Str::words(strip_tags($review->meta->first()->preview), 100, ' ...') }}
                </div>
                @endforeach
            @endif
            @if(!is_null($result['pages']) && $result['pages']->count())
                @foreach($result['pages'] as $page)
            <li>
                <div class="search-text">
                    {{ $page->metas->first()->name }}. {{ Str::words(strip_tags($page->metas->first()->content), 100, ' ...') }}
                </div>
                <a href="{{ link::to($page->slug) }}" class="post-link">Подробнее</a>
                @endforeach
            @endif
        </ol>
    @else
        <div class="search-text">
            Попробуйте изменить поисковый запрос
        </div>
    @endif
    </div>
</div>