<?php

class PublicNewsController extends BaseController {

    public static $name = 'news_public';
    public static $group = 'news';

    /****************************************************************************/
    /**
     * @author Alexander Zelensky
     * @todo Возможно, в будущем здесь нужно будет добавить проверку параметра, использовать ли вообще мультиязычность по сегментам урла, или нет. Например, удобно будет отключить мультиязычность по сегментам при использовании разных доменных имен для каждой языковой версии.
     */
    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        ## УРЛЫ С ЯЗЫКОВЫМИ ПРЕФИКСАМИ ДОЛЖНЫ ИДТИ ПЕРЕД ОБЫЧНЫМИ!
        ## Если в конфиге прописано несколько языковых версий...
        if (is_array(Config::get('app.locales')) && count(Config::get('app.locales')) > 1) {
            ## Для каждого из языков...
            foreach(Config::get('app.locales') as $locale_sign => $locale_name) {
            	## ...генерим роуты с префиксом (первый сегмент), который будет указывать на текущую локаль.
            	## Также указываем before-фильтр i18n_url, для выставления текущей локали.
                Route::group(array('before' => 'i18n_url', 'prefix' => $locale_sign), function() use ($class) {
                    Route::get('/news/{url}', array('as' => 'news_full', 'uses' => $class.'@showFullNews'));
                    Route::get('/news/',      array('as' => 'news',      'uses' => $class.'@showNews'));
                });
            }
        }

        ## Генерим роуты без префикса, и назначаем before-фильтр i18n_url.
        ## Это позволяет нам делать редирект на урл с префиксом только для этих роутов, не затрагивая, например, /admin и /login
        Route::group(array('before' => 'i18n_url'), function() {
            Route::get('/news/{url}', array('as' => 'news_full', 'uses' => __CLASS__.'@showFullNews'));
            Route::get('/news/',      array('as' => 'news',      'uses' => __CLASS__.'@showNews'));
        });
    }
    
    ## Shortcodes of module
    public static function returnShortCodes() {

        $tpl = static::returnTpl();

    	shortcode::add("news",
        
            function($params = null) use ($tpl) {

                #print_r($params); die;

                if(!Allow::module('news'))
                    return false;

                ## Параметры по-умолчанию
                $default = array(
                    'tpl' => Config::get('app-default.news_template', 'default'),
                    'limit' => Config::get('app-default.news_count_on_page', 3),
                    'order' => Helper::stringToArray(News::$order_by),
                    'pagination' => 1,
                    'type' => false,
                    'exclude_type' => false,
                    'include' => false,
                    'exclude' => false,
                );
        		## Применяем переданные настройки
                $params = $params+$default;
                #dd($params);

                #echo $tpl.$params['tpl'];

                if(empty($params['tpl']) || !View::exists($tpl.$params['tpl']))
                    throw new Exception('Template [' . $tpl.$params['tpl'] . '] not found.');

                $news = News::orderBy('news.published_at', 'desc')->with('meta.photo', 'meta.gallery.photos', 'meta.seo');

                /*
                ## Получаем новости, делаем LEFT JOIN с news_meta, с проверкой языка и тайтла
                $selected_news = News::where('news.publication', 1)
                                        ->leftJoin('news_meta', 'news_meta.news_id', '=', 'news.id')
                                        ->where('news_meta.language', Config::get('app.locale'))
                                        ->where('news_meta.title', '!=', '')
                                        ->select('*', 'news.id AS original_id', 'news.published_at AS created_at')
                                        ->orderBy('news.published_at', 'desc');

                #$selected_news = $selected_news->where('news_meta.wtitle', '!=', '');

                ## Получаем новости с учетом пагинации
                #echo $selected_news->toSql(); die;
                #var_dump($params['limit']);
                $news = $selected_news->paginate($params['limit']); ## news list with pagination
                #$news = $selected_news->get(); ## all news, without pagination
                */

                /**
                 * Показываем новости только определенных типов
                 */
                if (@$params['type']) {
                    $params['types'] = (array)explode(',', $params['type']);
                    #Helper::d($params);
                    if (
                        isset($params['types']) && is_array($params['types']) && count($params['types'])
                        && Allow::module('dictionaries') && class_exists('DicVal')
                    ) {
                        $types = DicVal::whereIn('slug', $params['types'])->get();
                        #Helper::tad($types);
                        if ($types->count()) {
                            $types = $types->lists('id');
                            #Helper::tad($types);
                            if (count($types))
                                $news = $news->whereIn('type_id', $types);
                        }
                    }
                }

                /**
                 * Исключаем новости определенных типов
                 */
                if (@$params['exclude_type']) {
                    $params['exclude_types'] = (array)explode(',', $params['exclude_type']);
                    #Helper::d($params);
                    if (
                        isset($params['exclude_types']) && is_array($params['exclude_types']) && count($params['exclude_types'])
                        && Allow::module('dictionaries') && class_exists('DicVal')
                    ) {
                        $types = DicVal::whereIn('slug', $params['exclude_types'])->get();
                        #Helper::tad($types);
                        if ($types->count()) {
                            $types = $types->lists('id');
                            #Helper::tad($types);
                            if (count($types))
                                $news = $news->whereNotIn('type_id', $types);
                        }
                    }
                }

                /**
                 * Будем выводить только новости, ID которых указаны
                 */

                if (@$params['include']) {
                    $params['includes'] = (array)explode(',', $params['include']);
                    if (isset($params['includes']) && is_array($params['includes']) && count($params['includes']))
                        $news = $news->whereIn('id', $params['includes']);
                }
                /**
                 * Исключаем новости с заданными ID
                 */
                if (@$params['exclude']) {
                    $params['excludes'] = (array)explode(',', $params['exclude']);
                    if (isset($params['excludes']) && is_array($params['excludes']) && count($params['excludes']))
                        $news = $news->whereNotIn('id', $params['excludes']);
                }

                $news = $news->paginate($params['limit']);

                #Helper::tad($news);

                /*
                foreach ($news as $n => $new) {
                    #print_r($new); die;
                    $gall = Rel_mod_gallery::where('module', 'news')->where('unit_id', $new->original_id)->first();
                    #foreach ($gall->photos as $photo) {
                    #	print_r($photo->path());
                    #}
                    #print_r($gall->photos); die;
                    $new->gall = @$gall;
                    $new->image = is_object(@$gall->photos[0]) ? @$gall->photos[0]->path() : "";
                    $news[$n]->$new;
                }
                */
                #echo $news->count(); die;

                if(!$news->count())
                    return false;

                return View::make($tpl.$params['tpl'], compact('news'));
    	    }
        );
        
    }

    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
    }

    /****************************************************************************/

	public function __construct(News $news, NewsMeta $news_meta){

        /*
        View::share('module_name', self::$name);
        $this->tpl = $this->gtpl = static::returnTpl();
        View::share('module_tpl', $this->tpl);
        View::share('module_gtpl', $this->gtpl);
        */

        $this->news = $news;
        $this->news_meta = $news_meta;
        $this->locales = Config::get('app.locales');

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            #'rest' => self::$group,
            'tpl' => static::returnTpl('admin'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            #'entity' => self::$entity,
            #'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
	}

    ## Функция для просмотра полной мультиязычной новости
    public function showNews() {

        if(!Allow::module($this->module['group']))
            App::abort(404);

        $limit = Config::get('site.news_page_limit', 10);
        $tpl = Config::get('site.news_page_template', 'news-list-page');

        $news = $this->news
            ->where('publication', 1)
            ->orderBy('published_at', 'DESC')
            ->with('meta.seo', 'meta.photo', 'meta.gallery.photos')
            ->take($limit)
            ->paginate($limit);

        #Helper::tad($news);

        if (!@count($news))
            App::abort(404);

        if (!$tpl)
            $tpl = 'news-list-page';

        #Helper::tad($news);

        #Helper::dd( $this->module['gtpl'].$tpl );

        if(empty($tpl) || !View::exists($this->module['gtpl'].$tpl))
            throw new Exception('Template [' . $this->module['gtpl'].$tpl . '] not found.');

        return View::make($this->module['gtpl'].$tpl, compact('news'));
    }


    ## Функция для просмотра полной мультиязычной новости
    public function showFullNews($url = false) {

        if(!Allow::module($this->module['group']))
            App::abort(404);

        if (!@$url)
            $url = Input::get('url');

        $news = $this->news->where('publication', 1);

        #dd($url);

        ## News by ID
        if (is_numeric($url)) {

            $slug = false;
            $news = $news->where('id', $url);
            $news = $news
                ->with('meta.seo', 'meta.photo', 'meta.gallery.photos')
                ->first();

            #Helper::tad($news);

            if (@is_object($news)) {

                if (@is_object($news->meta) && @is_object($news->meta->seo)) {
                    $slug = $news->meta->seo->url;
                } else {
                    $slug = $news->slug;
                }

                #$slug = false;
                #Helper::dd($slug);

                if ($slug) {
                    $redirect = URL::route('news_full', array('url' => $slug));
                    #Helper::dd($redirect);
                    return Redirect::to($redirect, 301);
                }
            }

        } else {

            ## News by SLUG

            ## Search slug in SEO URL
            $news_meta_seo = Seo::where('module', 'news_meta')->where('url', $url)->first();
            #Helper::tad($news_meta_seo);
            if (is_object($news_meta_seo) && is_numeric($news_meta_seo->unit_id)) {

                $news = $this->news_meta
                    ->where('id', $news_meta_seo->unit_id)
                    ->with(array('news' => function($query){
                            $query->with(
                                'meta.seo',
                                'meta.photo',
                                'meta.gallery.photos'
                            );
                        }))->first()->news;
                #Helper::tad($news);

            } else {

                ## Search slug in SLUG
                $news = $this->news
                    ->where('slug', $url)
                    ->with('meta.seo', 'meta.photo', 'meta.gallery.photos')
                    ->first();

                ## Check SEO url & gettin' $url
                ## and make 301 redirect if need it
                if (@is_object($news->meta) && @is_object($news->meta->seo) && $news->meta->seo->url != '' && $news->meta->seo->url != $url) {
                    $redirect = URL::route('news_full', array('url' => $news->meta->seo->url));
                    #Helper::dd($redirect);
                    return Redirect::to($redirect, 301);
                }
            }

        }

        #Helper::tad($news);

        if (!@is_object($news) || !@is_object($news->meta))
            App::abort(404);

        if (!$news->template)
            $news->template = 'default';

        #Helper::tad($news);

        if(empty($news->template) || !View::exists($this->module['gtpl'].$news->template))
            throw new Exception('Template [' . $this->module['gtpl'].$news->template . '] not found.');

        return View::make($this->module['gtpl'].$news->template, compact('news'));
    }

}