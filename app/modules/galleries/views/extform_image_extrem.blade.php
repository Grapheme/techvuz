
    <!-- Don't forget add to parent page to scripts section JS code for correct functionality, some like this: -->
    <!-- loadScript("/js/modules/gallery.js"); -->

    <div>
    	<div class="egg-dropzone-single dropzone" data-name="{{ $name }}" data-gallery_id="0"></div>
        <div class="superbox_ col-sm-12 photo-preview-container" style="margin-top:10px; display:none">
                        
        	<div class="photo-preview photo-preview-single" style="display:none;">
        		<a href="#photo-path" target="_blank" title="Полноразмерное изображение" class="photo-full-link" style="display:block; height:100%; color:#090; background:transparent"></a>
        		<a href="#" class="photo-delete-single" data-photo-id="#photo-id" style="">Удалить</a>
        	</div>

            
            <div>
                <a href="#" class="btn" onClick="$('.photo-form').submit();">ГОТОВО!</a>
            </div>
            
                        
        </div>
    </div>
