
@if(isset($news) && is_object($news) && $news->count())

    {{ Helper::tad_($news) }}

    <ul class="actions-ul">
        @foreach($news as $new)
        <?
        if (@!is_object($new->meta) || !$new->meta->title)
            continue;

        $photo = false;
        if (@is_object($new->meta->photo))
            $photo = $new->meta->photo->thumb();
        $gallery = false;
        if (@is_object($new->meta->gallery) && @is_object($new->meta->gallery->photos) && @count($new->meta->gallery->photos))
            $gallery = $new->meta->gallery->photos;
        ?>
        <li class="actions-li row clearfix">
            <div class="column third">
                <a href="#" class="action-img" style="background-image:url({{ $photo }});"></a>
            </div>
            <div class="column two-thirds">
                <h2>
                    <a href="#">{{ $new->meta->title }}</a>
                </h2>
                <div class="section-desc">
                    @if (Config::get('app.locale') == 'ru')
                        {{ Helper::rdate("d M Y", strtotime($new->published_at)) }}
                    @else
                        {{ date("d M Y", strtotime($new->published_at)) }}
                    @endif
                </div>
                <p>
                    {{ $new->meta->preview }}
                </p>
            </div>
        </li>
        @endforeach
    </ul>

	{{-- $news->links() --}}

@endif
