<?php

class AdminPagesPageController extends BaseController {

    public static $name = 'pages_page';
    public static $group = 'pages';
    public static $entity = 'page';
    public static $entity_name = 'страница';

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

        Route::post('ajax-pages-get-page-blocks', $class.'@postAjaxPagesGetPageBlocks');
        Route::post('ajax-pages-get-block', $class.'@postAjaxPagesGetBlock');
        Route::post('ajax-pages-delete-block', $class.'@postAjaxPagesDeleteBlock');
        Route::post('ajax-pages-save-block', $class.'@postAjaxPagesSaveBlock');
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
    }

    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
    }

    ## Menu elements of the module
    public static function returnMenu() {
    }

    /****************************************************************************/

	public function __construct(Page $essence, PageMeta $pages_meta, PageBlock $pages_blocks, PageBlockMeta $pages_blocks_meta) {

        $this->essence = $essence;
        $this->pages_meta = $pages_meta;
        $this->pages_blocks = $pages_blocks;
        $this->pages_blocks_meta = $pages_blocks_meta;

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

	public function index(){

        Allow::permission($this->module['group'], 'view');

		$pages = $this->essence
            ->orderBy('start_page', 'DESC')
            ->orderBy('order', 'ASC')
            ->with('blocks')
            ->get();

        #Helper::tad($pages);

        $locales = $this->locales;

		return View::make($this->module['tpl'].'index', compact('pages', 'locales'));
	}

    public function create(){

        Allow::permission($this->module['group'], 'create');

        $element = new $this->essence;
        $locales = $this->locales;
        foreach ($this->templates(__DIR__) as $template)
            @$templates[$template] = $template;

        #Helper::dd($locales);

        return View::make($this->module['tpl'].'edit', compact('element', 'locales', 'templates'));
    }

    public function edit($id){

        Allow::permission($this->module['group'], 'edit');

        $element = $this->essence->where('id', $id)
            ->with('metas.seo')
            ->with('blocks')
            ->first();

        ##
        ## Получение страницы, с языковой МЕТА, и блоками (с языковой МЕТА) со SLUG-ключами
        ##
        #$element = Page::where('id', $id)->with('meta')->with('blocks.meta')->first()->blocksBySlug();

        #Helper::tad($element);

        if (!is_object($element))
            return Redirect::route($this->module['entity'] . '.index');

        $locales = $this->locales;
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
        $blocks = Helper::withdraw($input, 'blocks');
        $blocks_new = Helper::withdraw($input, 'blocks_new');
        #$seo = Helper::withdraw($input, 'seo');

        $input['template'] = @$input['template'] ? $input['template'] : NULL;

        $input['slug'] = @$input['slug'] ? $input['slug'] : $input['name'];
        $input['slug'] = Helper::translit($input['slug']);

        $input['start_page'] = @$input['start_page'] ? 1 : NULL;

        #$json_request['responseText'] = "<pre>" . print_r(Input::all(), 1) . "</pre>";
        $json_request['responseText'] = "<pre>" . print_r($input, 1) . print_r($locales, 1) . print_r($blocks, 1) . print_r($blocks_new, 1) . "</pre>";
        #return Response::json($json_request,200);

        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
        $validator = Validator::make($input, $this->essence->rules());
        if($validator->passes()) {

            $redirect = false;

            ## PAGES
            if ($id != false && $id > 0 && $this->essence->find($id)->exists()) {

                $element = $this->essence->find($id);
                $element->update($input);

                ## PAGES_BLOCKS - update
                if (count($blocks)) {
                    foreach ($blocks as $block_id => $block_data) {
                        $block_data['slug'] = @$block_data['slug'] ? $block_data['slug'] : $block_data['name'];
                        $block_data['slug'] = Helper::translit($block_data['slug']);
                        $block = $this->pages_blocks->find($block_id);
                        if (is_object($block)) {
                            $block->update($block_data);
                        }
                    }
                }

            } else {

                $element = $this->essence->create($input);
                $id = $element->id;

                $redirect = URL::route($this->module['entity'].'.edit', array('page_id' => $id));
            }

            if (!is_null($element->start_page))
                $this->essence->where('start_page', 1)->where('id', '!=', $element->id)->update(array('start_page' => NULL));

            ## PAGES_META
            if (count($locales)) {
                foreach ($locales as $locale_sign => $locale_settings) {

                    $seo = Helper::withdraw($locale_settings, 'seo');
                    $locale_settings['template'] = @$locale_settings['template'] ? $locale_settings['template'] : NULL;
                    $page_meta = $this->pages_meta->where('page_id', $element->id)->where('language', $locale_sign)->first();
                    if (is_object($page_meta)) {
                        $page_meta->update($locale_settings);
                    } else {
                        $locale_settings['page_id'] = $id;
                        $locale_settings['language'] = $locale_sign;
                        $page_meta = $this->pages_meta->create($locale_settings);
                    }

                    ## PAGES META SEO
                    #if (isset($seo[$locale_sign])) {
                    if (isset($seo)) {

                        ###############################
                        ## Process SEO
                        ###############################
                        $seo_result = ExtForm::process('seo', array(
                            'module'  => 'page_meta',
                            'unit_id' => $page_meta->id,
                            'data'    => $seo,
                        ));
                        #Helper::tad($seo_result);
                        ###############################
                    }
                }
            }

            ## PAGES_BLOCKS - create
            if (count($blocks_new)) {
                foreach ($blocks_new as $null => $block_data) {
                    $block_data['page_id'] = $id;
                    $block_data['slug'] = @$block_data['slug'] ? $block_data['slug'] : $block_data['name'];
                    $block_data['slug'] = Helper::translit($block_data['slug']);
                    $this->pages_blocks->create($block_data);
                }
            }

            #$json_request['responseText'] = Helper::d($redirect);
            #return Response::json($json_request,200);

            $json_request['responseText'] = 'Сохранено';
            if ($redirect)
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
            return App::abort(404);

        Allow::permission($this->module['group'], 'delete');

        $json_request = array('status'=>FALSE, 'responseText'=>'');

        $page = $this->essence->find($id);
        if (is_object($page)) {

            $metas = $this->pages_meta->where('page_id', $id)->get();
            if (count($metas)) {
                foreach ($metas as $meta)
                    $meta->delete();
            }

            $blocks = $this->pages_blocks->where('page_id', $id)->get();
            if (count($blocks)) {
                foreach ($blocks as $block) {
                    $block_metas = $this->pages_blocks_meta->where('block_id', $block->id)->get();
                    foreach ($block_metas as $block_meta) {
                        $block_meta->delete();
                    }
                    $block->delete();
                }
            }

        }
        $page->delete();

        $json_request['responseText'] = 'Страница удалена';
        $json_request['status'] = TRUE;

		return Response::json($json_request,200);
	}

    public function postAjaxPagesDeleteBlock() {

        if(!Request::ajax())
            return App::abort(404);

        $id = Input::get('id');
        #Helper::d($id);
        $block = PageBlock::where('id', $id)->with('metas')->first();
        #Helper::tad($block);
        if (is_object($block)) {
            if (count($block->metas)) {
                foreach ($block->metas as $meta) {
                    $meta->delete();
                }
            }
            $block->delete();
            return 1;
        }
        return 0;
    }

    public function postAjaxPagesGetPageBlocks() {

        if(!Request::ajax())
            return App::abort(404);

        $id = Input::get('id');
        $blocks = PageBlock::where('page_id', $id)->with('metas')->orderBy('order')->get();
        #return $blocks->toJson();

        $return = '';
        if (count($blocks)) {
            foreach ($blocks as $block) {
                $return .= View::make($this->module['tpl'].'_block', compact('block'));
            }
        }
        return $return;
    }

    public function postAjaxPagesBlocksOrderSave() {

        if(!Request::ajax())
            return App::abort(404);

        $poss = Input::get('poss');

        $pls = PageBlock::whereIn('id', $poss)->get();

        if ( $pls ) {
            foreach ( $pls as $pl ) {
                $pl->order = array_search($pl->id, $poss);
                $pl->save();
            }
        }

        return Response::make('1');
    }

    public function postAjaxPagesGetBlock() {

        if(!Request::ajax())
            return App::abort(404);

        $element = PageBlock::where('id', Input::get('id'))->with('metas')->orderBy('order')->first()->metasByLang();
        #return $block->toJson();

        $locales = $this->locales;

        #Helper::dd($this->templates(__DIR__, '/views/tpl_block'));

        foreach ($this->templates(__DIR__, '/views/tpl_block') as $template)
            @$templates[$template] = $template;


        return View::make($this->module['tpl'].'_block_edit', compact('element', 'locales', 'templates'));

    }


    public function postAjaxPagesSaveBlock() {

        #if(!Request::ajax())
        #    return App::abort(404);

        /*
        if (Input::get('id'))
            $block = PageBlock::where('id', Input::get('id'))->first();
        else
            $block = new PageBlock;
        */

        $id = Input::get('id');
        $input = Input::all();
        $locales = Helper::withdraw($input, 'locales');
        $input['template'] = @$input['template'] ? $input['template'] : NULL;
        $input['slug'] = @$input['slug'] ? $input['slug'] : $input['name'];
        $input['slug'] = Helper::translit($input['slug']);

        $validator = Validator::make($input, $this->pages_blocks->rules());
        if($validator->passes()) {

            $redirect = false;

            ## BLOCK
            if ($id != false && $id > 0 && $this->pages_blocks->find($id)->exists()) {

                $element = $this->pages_blocks->find($id);
                $element->update($input);

            } else {

                $element = $this->pages_blocks->create($input);
                $id = $element->id;
            }

            ## BLOCK_META
            if (count($locales)) {
                foreach ($locales as $locale_sign => $locale_settings) {
                    $locale_settings['template'] = @$locale_settings['template'] ? $locale_settings['template'] : NULL;
                    $block_meta = $this->pages_blocks_meta->where('block_id', $element->id)->where('language', $locale_sign)->first();
                    if (is_object($block_meta)) {
                        $block_meta->update($locale_settings);
                    } else {
                        $locale_settings['block_id'] = $id;
                        $locale_settings['language'] = $locale_sign;
                        $this->pages_blocks_meta->create($locale_settings);
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
        #return '';
    }
}
