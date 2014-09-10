
    <!-- Don't forget add to parent page to scripts section JS code for correct functionality, some like this: -->
    <!-- loadScript("/js/modules/gallery.js"); -->

    <div>
    	<div class="egg-dropzone-single dropzone" data-name="{{ $name }}" data-gallery_id="0"<? if (@is_object($photo)) { echo " style='display:none'";} ?>></div>
        <div class="superbox_ photo-preview-container" style="margin-top:10px;">
{{--        	<input type="hidden" name="{{ $name }}[gallery_id]" value="0" />--}}
            @if (@is_object($photo))

            	<input type="hidden" name="{{ $name }}[uploaded_image]" value="{{ $photo->id }}" id="uploaded_image_{{ $photo->id }}" />

            	<div class="photo-preview photo-preview-single" style="background-image:url({{ $photo->thumb() }});">
            		<a href="{{ $photo->path() }}" target="_blank" title="Полноразмерное изображение" style="display:block; height:100%; color:#090; background:transparent"></a>
            		<a href="#" class="photo-delete-single" data-photo-id="{{ $photo->id }}" style="">Удалить</a>
            	</div>
            @else

                <input type="hidden" name="{{ $name }}[uploaded_image]" value="" id="uploaded_image_false" />

            	<div class="photo-preview photo-preview-single" style="display:none;">
            		<a href="#photo-path" target="_blank" title="Полноразмерное изображение" class="photo-full-link" style="display:block; height:100%; color:#090; background:transparent"></a>
            		<a href="#" class="photo-delete-single" data-photo-id="#photo-id" style="">Удалить</a>
            	</div>
            @endif
        </div>
    </div>