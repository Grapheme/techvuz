<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/


ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/modules',
	app_path().'/models',
	app_path().'/database/seeds',
	app_path().'/lib',

));


/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
    #return Response::view('error500', array(), $code);
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';



/*
|--------------------------------------------------------------------------
| Переадресовываем uspensky-pk.com на немецкую версию
|--------------------------------------------------------------------------
*/
/*
    if (
        isset($_SERVER['HTTP_HOST'])
        && $_SERVER['HTTP_HOST'] != ''
        && stripos(@$_SERVER['HTTP_HOST'], ".com")
        && Request::segment(1) != 'de'
        && Session::get('locale') == ''
    ) {
    	Session::put('locale', 'de');
        Redirect("/de");
    }
*/

/*
|--------------------------------------------------------------------------
| Определяем язык сайта для всего приложения
|--------------------------------------------------------------------------
*/
#/*
    ## Получаем все активные языки сайта
    $locales = Config::get('app.locales');
    ## Если языков больше, чем 1...
    if (count($locales) > 1) {
        ## Запомним язык по-умолчанию
        Config::set('app.default_locale', Config::get('app.locale'));

        ## Если в данный момент первый сегмент урла соответствует какому-то из наших языков - делаем его активным
        if ( @$locales[Request::segment(1)] ) {

            /*
             * Задумка №1: вырезать первый языковой сегмент урла, если он совпадает дефолтным языком.
             * Это позволить открывать страницы сайта на дефолтном языке по адресу /{url}, а прочие языковые версии по адресу /en/{url}.
             * Однако это несет в себе проблему генерации ссылок на страницу прочих языковых версий, поэтому было принято решение
             * и страницы на дефолтном языке вынести в сегмент /ru/{url}, за исключением главной страницы - она всегда будет по адресу /.
             */
            /*
            ## Если первым сегментом идет дефолтный язык
            if (Request::segment(1) === Config::get('app.default_locale') && FALSE) {
                ## Вырезаем первый сегмент с дефолтным языком
                $path_without_first_segment = preg_replace("~^" . Config::get('app.default_locale') . "~s", '', Request::path(), 1);
                #$path_without_first_segment = preg_replace("~^/~s", '', $path_without_first_segment, 1);
                #Helper::dd( $path_without_first_segment . " => " . URL::to($path_without_first_segment) );
                ## Здесь не сработает return Redirect::to(...), так что делаем переадресацию через кастомную функцию
                Redirect(URL::to($path_without_first_segment));
            }
            */

            #Helper::dd(Request::segment(1));

            ## Если запрос состоит всего лишь из одного урл-сегмента, и он совпадает с дефолтным языком
            if (Request::path() === Config::get('app.default_locale')) {
                Redirect(URL::to('/'));
            }

            ## Установим дефолтную локаль
            Config::set('app.locale', Request::segment(1));
            App::setLocale(Request::segment(1));

        } else {

            #Helper::dd(Request::path());

            ##
            ## Данный кусок кода перенесен в filters.php, в фильтр i18n_url, чтобы редирект происходил только для использующих before-фильтр роутов.
            ##
            /*
            ## Если не главная страница - будем подставлять первым сегментом дефолтную локаль
            if (Request::path() != '/') {
                ## А если нет - подставим дефолтную локаль и сделаем редирект
                #Helper::dd(Config::get('app.locale') . '/' . Request::path());
                Redirect(URL::to(Config::get('app.locale') . '/' . Request::path()));
            }
            */
        }

        ## Сохраняем в сессию текущий язык
        Session::put('locale', Config::get('app.locale'));
    }
    #Helper::d( '/' . Request::segment(1) . '/ - (' . Config::get('app.default_locale') . ') => (' . Config::get('app.locale') . ") - " . Session::get('locale') );
#*/

#Event::listen('illuminate.query', function($query){ echo $query . "<br/>\n"; });
