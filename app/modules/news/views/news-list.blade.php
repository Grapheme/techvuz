
@if(isset($news) && is_object($news) && $news->count())

    {{ Helper::tad_($news) }}

    <ul class="news-list">
	@foreach($news as $new)
        <?
        $photo = false;
        if (@is_object($new->meta) && @is_object($new->meta->photo))
            $photo = $new->meta->photo->thumb();
        ?>
        <li class="news-item">
            <div class="news-cont">
                <div class="news-photo" style="background-image: url({{ $photo }});"></div>
                <p class="news-date">{{ date("d/m/Y", strtotime($new->published_at)) }}</p>
                <h3>
                    <a href="{{ URL::route('news_full', array('url' => $new->slug)) }}">{{ $new->meta->title }}</a>
                </h3>
                <div class="news-desc">
                    {{ $new->meta->preview }}
                </div>
            </div>
        </li>
	@endforeach
    </ul>
	{{ $news->links() }}
@endif
