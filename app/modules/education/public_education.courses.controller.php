<?php

class PublicEducationCoursesController extends BaseController {

    public static $name = 'courses';
    public static $group = 'education';
    public static $entity = 'courses';
    public static $entity_name = 'Курс';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::get('/catalog/{url}',array('as'=>'course-page','uses'=>$class.'@index'));
    }

    public static function returnShortCodes() {
        return NULL;
    }

    public static function returnActions() {
        return NULL;
    }

    public static function returnInfo() {
        return NULL;
    }

    public static function returnMenu() {
        return NULL;
    }

    /****************************************************************************/

    protected $course;

    public function __construct(Courses $course){

        $this->course = $course;

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl(),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,
        );
        View::share('module', $this->module);
    }

    public function index($url = FALSE) {

        if (!@$url)
            $url = Input::get('url');

        if($course_seo = Seo::where('module','education-courses')->where('url', $url)->first()):
            $course = $this->course->where('id', $course_seo->unit_id)
                ->with('metodical','direction','seo')->first();
//            Helper::tad($course);
        else:
            App::abort(404);
        endif;

        return View::make('templates.'.Config::get('app.template').'.course', compact('course'));
    }

}