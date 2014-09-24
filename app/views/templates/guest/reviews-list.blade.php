<?php
$reviews = Dictionary::whereSlugValues('reviews');
$images_ids = array();
foreach($reviews as $index => $review):
    $review['fields'] = modifyKeys($review['fields'],'key');
    if(!empty($review->fields['user_avatar']->value)):
        $images_ids[] = $review->fields['user_avatar']->value;
    endif;
endforeach;
 $images = Photo::whereIn('id',$images_ids)->get();
 $images = modifyKeys($images,'id',true);
?>

<section class="reviews">
    <h3>Отзывы</h3>
    <ul class="reviews-ul">
    @foreach($reviews as $review)
    <?php
        $review['fields'] = modifyKeys($review['fields'],'key');
    ?>
        <li class="reviews-li">
            <div class="reviews-author">
                @if(isset($images[$review->fields['user_avatar']->value]))
                <div class="reviews-author-ava">
                    <img src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$images[$review->fields['user_avatar']->value]->name) }}" alt="">
                </div>
                @endif
                <div class="reviews-author-name">
                    {{ $review->name }}
                </div>
                <div class="reviews-author-spec">
                    {{ $review->fields['user_position']->value }}
                </div>
            </div>
            <div class="reviews-text">
                {{ $review->fields['description']->value }}
            </div>
        </li>
    @endforeach
    </ul>
</section>