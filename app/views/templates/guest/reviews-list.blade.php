<?php
$dic_reviews = Dictionary::where('slug','reviews')->with('random_values')->first();
$reviews = $dic_reviews->random_values;
$images_ids = array();
if(count($reviews)):
    foreach($reviews as $index => $review):
        $review['fields'] = modifyKeys($review['fields'],'key');
        if(!empty($review->fields['user_avatar']->value)):
            $images_ids[] = $review->fields['user_avatar']->value;
        endif;
    endforeach;
    if(!empty($images_ids)):
        $images = Photo::whereIn('id',$images_ids)->get();
        $images = modifyKeys($images,'id',true);
    else:
        $images = array();
    endif;
endif;
?>
@if($reviews->count())
<section class="reviews">
    <h3><a href="{{ URL::route('page','reviews') }}">Отзывы</a></h3>
    <ul class="reviews-ul">
    @foreach($reviews as $review)
    <?php $review['fields'] = modifyKeys($review['fields'],'key'); ?>
        <li class="reviews-li">
            <div class="reviews-author">
                <div class="reviews-author-ava">
                @if(isset($review->fields['user_avatar']->value) && isset($images[$review->fields['user_avatar']->value]))
                    <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$images[$review->fields['user_avatar']->value]->name) }}" alt="{{ $review->name }}">
                @else
                    <img src="{{ asset(Config::get('site.theme_path').'/img/avatars/default.png') }}" alt="{{ $review->name }}">
                @endif
                 </div>
                <div class="reviews-author-name">
                    {{ $review->name }}
                </div>
                <div class="reviews-author-spec">
                @if(isset($review->fields['user_avatar']->value))
                    {{ $review->fields['user_position']->value }}
                @endif
                </div>
            </div>
            <div class="reviews-text">
            @if(isset($review->fields['description']->value))
                {{ $review->fields['description']->value }}
            @endif
            </div>
        </li>
    @endforeach
    </ul>
</section>
@endif