<?php
$dicID = Dictionary::where('slug','reviews')->pluck('id');
$reviews = DicVal::where('dic_id',$dicID)->where('version_of',NULL)->with('fields')->paginate(Config::get('site.news_page_limit',5));
$images_ids = array();
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
?>
@extends(Helper::layout())
@section('style') @stop
@section('content')
    <main>
        <h2>Отзывы</h2>
        <div class="margin-top-40">
        @foreach($reviews as $review)
            <div class="row margin-bottom-20">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 style-light">
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
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 style-light no-padding padding-right-20">
                @if(isset($review->fields['description']->value))
                    {{ $review->fields['description']->value }}
                @endif
                </div>
            </div>
        @endforeach
            <div class="text-center">
                {{ $reviews->links(); }}
            </div>
        </div>
    </main>
@stop
@section('overlays')
@stop
@section('scripts')
    @if(Auth::guest() && Input::has('login'))
        <script>
            $(function(){
                $(".js-login").click();
            });
        </script>
    @endif
@stop