
    <!-- Don't forget add to parent page to scripts section JS code for correct functionality, some like this: -->
    <!-- loadScript("/js/plugin/superbox/superbox.min.js"); -->
    <!-- loadScript("/js/modules/gallery.js"); -->


	<div class="egg-dropzone dropzone" data-gallery_id="{{ (isset($gallery) && is_object($gallery)) ? (int)$gallery->gallery_id : '0' }}"></div>
    <div class="superbox col-sm-12" style="margin-top:10px;">

    	<input type="hidden" name="gallery_id" value="{{ (isset($gallery) && is_object($gallery)) ? (int)$gallery->gallery_id : '0' }}" />

        @if (@is_object($gallery))
            <? $gallery_photos = @$gallery->photos; ?>
            @if ($gallery_photos)

            	@foreach ($gallery_photos as $photo)
                {{--
        		<div class="superbox-list">
        			<img src="{{ $photo->thumb() }}" data-img="{{ $photo->full() }}" alt="" title="" class="superbox-img">
        		</div>
                --}}
            	<div style="display:inline-block; width:100px; height:100px; background:url({{ $photo->thumb() }}) no-repeat 50% 50%; background-size:cover; overflow:hidden; position:relative;">
            	<!--<div style="display:inline-block; height:100px; overflow:hidden; position:relative;">-->
            		<!--<img src="{{ $photo->thumb() }}" data-img="{{ $photo->path() }}" alt="" title="" style="height:100px">-->
            		<a href="{{ $photo->path() }}" target="_blank" title="Полноразмерное изображение" style="display:block; height:100%; color:#090; background:transparent"></a>
            		<a href="#" class="photo-delete" data-photo-id="{{ $photo->id }}" style="position:absolute; left:0; bottom:0; text-align:center; width:100%; color:#f00; background:#000">Удалить</a>
            	</div>
            	@endforeach

            @endif
        @endif
    	<div class="superbox-float"></div>    	
    </div>
    <div class="superbox-show" style="height:300px; display: none"></div>
