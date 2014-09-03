<?php

class AdminNewsController extends BaseController {

    public static $name = 'news';
    public static $group = 'news';
    public static $entity = 'news';
    public static $entity_name = 'новость';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            $entity = $class::$entity;
            Route::resource($class::$group /* . "/" . $entity */, $class,
                array(
                    'except' => array('show'),
                    'names' => array(
                        'index'   => $entity.'.index',
                        'create'  => $entity.'.create',
                        'store'   => $entity.'.store',
                        'edit'    => $entity.'.edit',
                        'update'  => $entity.'.update',
                        'destroy' => $entity.'.destroy',
                    )
                )
            );
        });

    }

    ## Shortcodes of module
    public static function returnShortCodes() {
    }

    ## Actions of module (for distribution rights of users)
    ## return false;   # for loading default actions from config
    ## return array(); # no rules will be loaded
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
        	'title' => 'Новости',
            'visible' => 1,
        );
    }

    ## Menu elements of the module
    public static function returnMenu() {
        return array(
            array(
            	'title' => 'Новости',
                'link' => self::$group,
                'class' => 'fa-calendar', 
                'permit' => 'view',
            ),
        );
    }

    /****************************************************************************/

	protected $news;

	public function __construct(News $news, NewsMeta $news_meta){

        $this->essence = $news;

		$this->news = $news;
		$this->news_meta = $news_meta;
        $this->locales = Config::get('app.locales');

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
	}

	public function index() {

        Allow::permission($this->module['group'], 'view');

		$news = $this->news
            #->with('seo') ## nope.
            #->with('meta.seo', 'meta.photo', 'meta.gallery.photos') ## works well!
            ->orderBy('published_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(Config::get('site.paginate_limit', 30))->appends($_GET);

        #Helper::tad($news);

        $locales = $this->locales;

		return View::make($this->module['tpl'].'index', compact('news', 'locales'));
	}

	public function create() {

        Allow::permission($this->module['group'], 'create');

        $element = new $this->news;
        $locales = $this->locales;
        $templates = array();
        foreach ($this->templates(__DIR__) as $template)
            @$templates[$template] = $template;

		return View::make($this->module['tpl'].'edit', compact('element', 'locales', 'templates'));
	}

    public function edit($id){

        Allow::permission($this->module['group'], 'edit');

        $element = $this->essence->where('id', $id)
            ->with('metas.seo')
            #->with('seo') ## nope again.
            #->with('type')
            ->first();

        #Helper::tad($element);

        if (!is_object($element))
            return Redirect::route($this->module['entity'] . '.index');

        $locales = $this->locales;
        $templates = array();
        foreach ($this->templates(__DIR__) as $template)
            @$templates[$template] = $template;

        #Helper::dd($locales);

        return View::make($this->module['tpl'].'edit', compact('element', 'locales', 'templates'));
    }

    public function store(){

        return $this->postSave();
    }

    public function update($id){

        return $this->postSave($id);
    }

    public function postSave($id = false){

        Allow::permission($this->module['group'], 'create');

        if(!Request::ajax())
            return App::abort(404);

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);

        $input = Input::all();
        $locales = Helper::withdraw($input, 'locales');
        $seo = Helper::withdraw($input, 'seo');
        $input['template'] = @$input['template'] ? $input['template'] : NULL;
        $input['slug'] = @$input['slug'] ? $input['slug'] : @$locales[Config::get('app.locale')]['title'];
        $input['slug'] = Helper::translit($input['slug']);
        $input['published_at'] = @$input['published_at'] ? date('Y-m-d', strtotime($input['published_at'])) : NULL;

        $json_request['responseText'] = "<pre>" . print_r(Input::all(), 1) . "</pre>";
        #$json_request['responseText'] = "<pre>" . print_r($input, 1) . print_r($locales, 1) . print_r($seo, 1) . "</pre>";
        #return Response::json($json_request,200);

        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
        $validator = Validator::make($input, $this->essence->rules());
        if($validator->passes()) {

            $redirect = false;

            ## NEWS
            if ($id != false && $id > 0 && $this->essence->find($id)->exists()) {

                $element = $this->essence->find($id);
                $element->update($input);

            } else {

                $element = $this->essence->create($input);
                $id = $element->id;

                $redirect = URL::route($this->module['entity'].'.edit', array('news_id' => $id));
            }

            ## NEWS_META
            if (count($locales)) {

                foreach ($locales as $locale_sign => $locale_settings) {

                    ## Withdraw gallery_id
                    $gallery_data = Helper::withdraw($locale_settings, 'gallery_id');

                    #$locale_settings['template'] = $locale_settings['template'] ? $locale_settings['template'] : NULL;
                    $news_meta = $this->news_meta->where('news_id', $element->id)->where('language', $locale_sign)->first();
                    if (is_object($news_meta)) {
                        $news_meta->update($locale_settings);
                    } else {
                        $locale_settings['news_id'] = $id;
                        $locale_settings['language'] = $locale_sign;
                        $news_meta = $this->news_meta->create($locale_settings);
                    }

                    ## NEWS_META GALLERY
                    if (isset($gallery_data)) {

                        ###############################
                        ## Process GALLERY
                        ###############################
                        $gallery_id = ExtForm::process('gallery', array(
                            'module'  => 'news_meta',
                            'unit_id' => $news_meta->id,
                            'gallery' => $gallery_data,
                            'single'  => true,
                        ));
                        ###############################
                        $locale_settings['gallery_id'] = $gallery_id;
                        $news_meta->update($locale_settings);
                    }

                    ## NEWS_META SEO
                    if (isset($seo[$locale_sign])) {

                        ###############################
                        ## Process SEO
                        ###############################
                        $seo_result = ExtForm::process('seo', array(
                            'module'  => 'news_meta',
                            'unit_id' => $news_meta->id,
                            'data'    => $seo[$locale_sign],
                        ));
                        #Helper::tad($seo_result);
                        ###############################
                    }

                }
            }

            $json_request['responseText'] = 'Сохранено';
            if (@$redirect)
                $json_request['redirect'] = $redirect;
            $json_request['status'] = TRUE;
        } else {
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = $validator->messages()->all();
        }
        return Response::json($json_request, 200);
	}


    public function destroy($id){

        if(!Request::ajax())
            App::abort(404);

        Allow::permission($this->module['group'], 'delete');

        $json_request = array('status'=>FALSE, 'responseText'=>'');

        $element = $this->essence->find($id);
        if (is_object($element)) {

            $metas = $this->news_meta->where('news_id', $id)->get();
            if (count($metas)) {
                foreach ($metas as $meta)
                    $meta->delete();
            }
        }
        $element->delete();

        $json_request['responseText'] = 'Удалено';
        $json_request['status'] = TRUE;

        return Response::json($json_request,200);
    }

}


