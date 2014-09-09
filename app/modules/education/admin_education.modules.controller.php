<?php

class AdminEducationModulesController extends BaseController {

    public static $name = 'modules';
    public static $group = 'education';
    public static $entity = 'modules';
    public static $entity_name = 'Модули';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            Route::resource(AdminEducationDirectionsController::$group.'/'.AdminEducationDirectionsController::$name.'/{direction}/'.AdminEducationCoursesController::$name.'/{course}/'.$class::$name, $class,
                array(
                    'except' => array('show','create','store','edit','update','destroy'),
                    'names' => array(
                        'index'   => self::$entity.'.index',
                    )
                )
            );
        });
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

    protected $direction;
    protected $course;

    public function __construct(){

        $this->direction = Directions::where('id',Request::segment(4))->with('courses')->first();
        $this->course = Courses::where('id',Request::segment(6))->with(array('chapters'=>function($query){
            $query->orderBy('order');
            $query->with(array('lectures'=>function($query_lecture){
                $query_lecture->orderBy('order');
            }));
            $query->with('test');
        }))->with('test')->first();

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin.modules'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    public function index() {

        Allow::permission($this->module['group'], 'view');
        $direction = $this->direction;
        $course = $this->course;
        return View::make($this->module['tpl'].'index', compact('direction','course'));
    }
}