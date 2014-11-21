
    <ul class="index-news">
    	@foreach($news as $new)
        <li>
            <div class="news-date">{{ Helper::rdate("j M Y", strtotime($new->published_at)) }}</div>
            <a href="{{ URL::route('news') }}#{{ $new->slug }}" class="us-link">
                {{ $new->meta->title }}
            </a>
    	@endforeach
    </ul>
