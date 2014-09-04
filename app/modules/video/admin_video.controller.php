<?php

class AdminVideoController extends BaseController {

    public static $name = 'video';
    public static $group = 'video';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
        	#Route::get($class::$group.'/manage', array('uses' => $class.'@getIndex'));
        	Route::controller($class::$group, $class);
        });
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
    }

    ## Extended Form elements of module
    public static function returnExtFormElements() {

        $mod_tpl = static::returnTpl();
        $class = __CLASS__;

        ##
        ## EXTFORM VIDEO
        ##
        /*
        ################################################
        ## Process video
        ################################################
        */
    	ExtForm::add(
            ## Name of element
            "video",
            ## Closure for templates (html-code)
            function($name = 'video', $value = '', $params = null) use ($mod_tpl, $class) {
                ## default template
                $tpl = "extform_video";
                ## custom template
                if (@$params['tpl']) {
                    $tpl = $params['tpl'];
                    unset($params['tpl']);
                }
                $params['class'] = trim(@$params['class'] . ' video_image_upload');

                #Helper::dd($value);

                if ( $value === false || $value === null ) {
                    $val = Form::text($name);
                    preg_match("~value=['\"]([^'\"]+?)['\"]~is", $val, $matches);
                    #Helper::d($matches);
                    $val = (int)@$matches[1];
                    if ( $val > 0 ) {
                        $value = Video::firstOrNew(array('id' => $val));
                    }
                } elseif (is_numeric($value)) {
                    $value = Video::where('id', $value)->with('image')->first();
                }

                ## return view with form element
                return View::make($mod_tpl.$tpl, compact('name', 'value', 'params'))->render();
            },
            ## Processing results closure
            function($params) use ($mod_tpl, $class) {

                #Helper::dd($params);

                $embed = @$params['embed'] ?: NULL;
                $image_file = @$params['image_file'] ?: NULL;
                $video_id = @$params['video_id'] ?: false;
                $delete_image = @$params['delete_image'] ?: false;

                $return = isset($params['return']) ? $params['return'] : 'id';
                $module = isset($params['module']) ? $params['module'] : NULL;
                $unit_id = isset($params['unit_id']) ? $params['unit_id'] : NULL;

                if (!$embed && !$video_id && !$image_file && !$delete_image)
                    return NULL;

                ## Find record
                $element = false;
                if (is_numeric($video_id))
                    $element = Video::find($video_id);

                ## Delete
                if ($delete_image && is_object($element)) {

                    if (@is_object($element) && $element->image_id) {
                        $image = Photo::find($element->image_id);
                        if (is_object($image)) {
                            @unlink($image->fullpath());
                            $image->delete();
                        }
                    }

                    $element->image_id = NULL;
                    $element->update();
                }

                ## If new file uploaded
                if (is_object($image_file)) {

                    ## Upload image by Gallery controller
                    $uploader = new AdminGalleriesController;
                    $image = $uploader->postSingleupload($image_file, 'return_object_please');
                    unset($uploader);

                    /*
                    ## Custom move image
                    $dir = Config::get('app-default.galleries_photo_dir', public_path('uploads'));
                    $file_name = time() . "_" . rand(1000, 1999) . '.' . $image_file->getClientOriginalExtension();
                    $image_file->move($dir, $file_name);
                    $image_path = preg_replace("~^" . addslashes(public_path()) . "~is", '', $dir . '/' . $file_name);

                    ## Create photo
                    $image = new Photo;
                    $image->name = $file_name;
                    $image->gallery_id = NULL;
                    $image->save();
                    */
                }

                ## Create new upload object if file not found
                if (!is_object($element)) {
                    $element = new Video;
                    $element->save();
                }
                ## Update upload record
                $element->update(array(
                    #'title' => $title,
                    #'description' => $description,
                    'embed' => $embed,
                    'image_id' => @is_object($image) ? $image->id : $element->image_id,
                    'module' => $module,
                    'unit_id' => $unit_id,
                ));

                ## Return
                return @$element->$return;
            }
        );

    }
    
    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
        return array(
        	'view'   => 'Просмотр',
        	'create' => 'Создание',
        	'edit'   => 'Редактирование',
        	'delete' => 'Удаление',
        );
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        return array(
        	'name' => self::$name,
        	'group' => self::$group,
        	'title' => 'Видеофайлы',
            'visible' => 1,
        );
    }

    ## Menu elements of the module
    public static function returnMenu() {
        /*
        return array(
            array(
            	'title' => 'Видеофайлы',
                'link' => self::$group,
                'class' => 'fa-picture-o',
                'permit' => 'view',
            ),
        );
        */
    }

    /****************************************************************************/
    
	public function __construct(){
		
		$this->beforeFilter('galleries');
        $this->locales = Config::get('app.locales');

        View::share('module_name', self::$name);

        $this->tpl = static::returnTpl('admin');
        $this->gtpl = static::returnTpl();
        View::share('module_tpl', $this->tpl);
        View::share('module_gtpl', $this->gtpl);
	}
	
	public function getIndex(){
		
		$galleries = Gallery::orderBy('id', 'desc')->get();
		return View::make($this->tpl.'index', compact('galleries'));
	}

    /****************************************************************************/

	public function postCreate(){
		
		$input = Input::all();
		$validation = Validator::make($input, gallery::getRules());
		if($validation->fails()) {
			return Response::json($validation->messages()->toJson(), 400);
		} else {
			$id = Gallery::create($input)->id;
			$href = link::to('admin/galleries/edit/'.$id);
			return Response::json($href, 200);
		}
	}

    /****************************************************************************/
    
	public function getEdit($id){
		
        $gallery = Rel_mod_gallery::where('gallery_id', $id)->first();
		return View::make($this->tpl.'edit', compact('gallery', 'bread'));
	}

    /****************************************************************************/

	public function postDelete(){
		
		$id = Input::get('id');
		$gallery = Gallery::find($id);

		@Rel_mod_gallery::where('gallery_id', $gallery->id)->delete();
		$deleted = $gallery->delete();

		if($deleted) {
			return Response::json('success', 200);
		} else {
			return Response::json('error', 400);
		}
	}

    /****************************************************************************/

	public function postUpload(){
		
        return postAbstractupload();
	}

    ##
    ## Загрузка изображений в галерею и привязка галереи к сущности любого модуля
    ##
	public function postAbstractupload(){

        ## Upload gallery image
        $result = $this->uploadImage('file');

        ## Check response
		if($result['result'] == 'error') {
	        return Response::json($result, 400);
	        exit;
		}

        ## Get gallery
		$gallery_id = Input::get('gallery_id') ? (int)Input::get('gallery_id') : 0;

        ## Make photo object
		$photo = Photo::create(array(
			'name' => $result['filename'],
			'gallery_id' => $gallery_id,
		));

        ## All OK, return result
		$result['result'] = 'success';
		$result['image_id'] = $photo->id;
		$result['gallery_id'] = $gallery_id;
		$result['thumb'] = $photo->thumb();
		$result['full'] = $photo->full();
		return Response::json($result, 200);		
	}


    ##
    ## Загрузка одиночного изображения и пометка о его использовании
    ##
	public function postSingleupload(){

        ## Upload gallery image
        $result = $this->uploadImage('file');

        ## Check response
		if($result['result'] == 'error') {
	        return Response::json($result, 400);
	        exit;
		}

        ## Make photo object
		$photo = Photo::create(array(
			'name' => $result['filename'],
			'gallery_id' => 0,
		));

        ## All OK, return result
		$result['result'] = 'success';
		$result['image_id'] = $photo->id;
		$result['gallery_id'] = -1;
		$result['thumb'] = $photo->thumb();
		$result['full'] = $photo->full();
		return Response::json($result, 200);		
	}


    ##
    ## Общая функция загрузки изображений
    ##
    private function uploadImage($input_file_name = false) {

		$result = array('result' => 'error');

        ## Check data
		if(!Input::hasFile($input_file_name)) {
			$result['desc'] = 'No input file.';
	        return $result;
        }
        
		$file = Input::file('file');
		$rules = array(
        	'file' => 'image'
	    );	 
	    $validation = Validator::make(array('file' => $file), $rules);
	    if ($validation->fails()){
	    	$result['desc'] = 'This extension is not allowed.';
	        return $result;
	    }

        ## Check upload & thumb dir
		$uploadPath = Config::get('app-default.galleries_photo_dir');
		$thumbsPath = Config::get('app-default.galleries_thumb_dir');

		if(!File::exists($uploadPath))
			File::makeDirectory($uploadPath, 0777, TRUE);
		if(!File::exists($thumbsPath))
			File::makeDirectory($thumbsPath, 0777, TRUE);

        ## Generate filename
		$fileName = time()."_".rand(1000, 1999).'.'.Input::file($input_file_name)->getClientOriginalExtension();

        ## Get images resize parameters from config
		$thumb_size = Config::get('app-default.galleries_thumb_size');
		$photo_size = Config::get('app-default.galleries_photo_size');

        ## Get image width & height
        $image = ImageManipulation::make(Input::file($input_file_name)->getRealPath());
        $w = $image->width();
        $h = $image->height();

        if ($thumb_size > 0) {
            ## Normal resize
            $thumb_resize_w = $thumb_size;
            $thumb_resize_h = $thumb_size;
        } else {
            ## Resize "by the smaller side"
            $thumb_size = abs($thumb_size);
            ## Resize thumb & full-size images "by the smaller side".
            ## Declared size will always be a minimum.
            $thumb_resize_w = ($w > $h) ? null : $thumb_size;
            $thumb_resize_h = ($w > $h) ? $thumb_size : null;
        }
        ## Resize thumb image
        $thumb_upload_success = ImageManipulation::make(Input::file($input_file_name)->getRealPath())
                                                ->resize($thumb_resize_w, $thumb_resize_h, function($constraint){
                                                    $constraint->aspectRatio();
                                                    $constraint->upsize();
                                                })
                                                ->save($thumbsPath.'/'.$fileName);

        if ($photo_size > 0) {
            ## Normal resize
            $image_resize_w = $photo_size;
            $image_resize_h = $photo_size;
        } else {
            ## Resize "by the smaller side"
            $photo_size = abs($photo_size);
            ## Resize full-size images "by the smaller side".
            ## Declared size will always be a minimum.
            $image_resize_w = ($w > $h) ? null : $photo_size;
            $image_resize_h = ($w > $h) ? $photo_size : null;
        }
        ## Resize full-size image
		$image_upload_success = ImageManipulation::make(Input::file('file')->getRealPath())
                                                ->resize($image_resize_w, $image_resize_h, function($constraint){
                                                    $constraint->aspectRatio();
                                                    $constraint->upsize();
                                                })
                                                ->save($uploadPath.'/'.$fileName);

		if (!$thumb_upload_success || !$image_upload_success) {
	    	$result['desc'] = 'Error on the saving images.';
	        return $result;
		}

    	$result['result'] = 'success';
    	$result['filename'] = $fileName;
        
        return $result;
    }

	public function postPhotodelete() {

		$id = (int)Input::get('id');
        if ($id)
            $model = Photo::find($id);
        if (!is_null($model))
		    $db_delete = $model->delete();

		if(@$db_delete) {
			$file_delete = File::delete(Config::get('app-default.galleries_photo_dir').'/'.$model->name);
			$thumb_delete = File::delete(Config::get('app-default.galleries_thumb_dir').'/'.$model->name);
		}

		#if(@$db_delete && @$file_delete && @$thumb_delete) {
		#if(@$db_delete) {
			return Response::json('success', 200);
		#} else {
		#	return Response::json('error', 400);
		#}
	}

    /****************************************************************************/

    ##
    ## USE imagesToUnit()!
    ##
	public static function moveImagesToGallery($images = array(), $gallery_id = false) {

		if ( !isset($images) || !is_array($images) || !count($images) )
			return $gallery_id;

        ## Find gallery
        $gallery = $gallery_id ? Gallery::find($gallery_id) : null;

        ## If gallery not found - create her
		if (!$gallery) {
			$gallery = Gallery::create(array(
				'name' => 'noname',
			));
		}
 
        ## Get gallery ID
        $gallery_id = $gallery->id;

        ## Move all images to gallery
		foreach ($images as $i => $img_id) {
			$img = Photo::find($img_id);
			if (@$img) {
				$img->gallery_id = $gallery_id;
				#print_r($img);
				$img->save();
			}
		}

		return $gallery_id;
	}

    ##
    ## USE imagesToUnit()!
    ##
	public static function relModuleUnitGallery($module = '', $unit_id = 0, $gallery_id = 0) {

		if ( !@$module || !$unit_id || !$gallery_id )
			return false;

		$rel = Rel_mod_gallery::where('module', $module)->where('unit_id', $unit_id)->where('gallery_id', $gallery_id)->first();

		if (!is_object($rel) || !@$rel->id) {
			$rel = Rel_mod_gallery::create(array(
				'module' => $module,
				'unit_id' => $unit_id,
				'gallery_id' => $gallery_id,
			));
		}

        self::renameGallery($gallery_id, $module . " - " . $unit_id);

		return $rel->id;
	}

    public static function renameGallery($gallery_id = false, $name = false) {
        if ( !$gallery_id || !$name )
            return false;

        $gallery = Gallery::find($gallery_id);
        if (is_object($gallery)) {
            $gallery->name = $name;
            $gallery->save();
            return true;
        }

        return false;
    }

	public static function imagesToUnit($images = array(), $module = '', $unit_id = 0, $gallery_id = false) {

		if (
			!isset($images) || !is_array($images) || !count($images)
			|| !@$module || !$unit_id
		)
			return $gallery_id;

		$gallery_id = self::moveImagesToGallery($images, $gallery_id);
		self::relModuleUnitGallery($module, (int)$unit_id, $gallery_id);


		return $gallery_id;
	}

}


