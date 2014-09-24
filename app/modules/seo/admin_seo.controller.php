<?php

class AdminSeoController extends BaseController {

    public static $name = 'seo';
    public static $group = 'seo';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
    }

    ## Extended Form elements of module
    public static function returnExtFormElements() {

        $mod_tpl = static::returnTpl();
        $class = __CLASS__;

        ##
        ## EXTFORM SEO
        ##
    	ExtForm::add(
            ## Name of element
            "seo",
            ## Closure for templates (html-code)
            function($name = 'seo', $value = '', $params = null) use ($mod_tpl, $class) {
                if (!Allow::action('seo', 'edit', true))
                    return false;

                ## default template
                $tpl = "extform_seo";
                ## custom template
                if (@$params['tpl']) {
                    $tpl = $params['tpl'];
                    unset($params['tpl']);
                }

                #$value = $element_meta->seo;
                /*
                ## Don't work with i18n versions
                if ( $value === false || $value === null ) {
                    $val = Form::text($name);
                    Helper::dd($val);
                    preg_match("~value=['\"]([^'\"]+?)['\"]~is", $val, $matches);
                    Helper::dd($matches);
                    $val = @$matches[1];
                    $array = json_decode($val, true);
                    if ($array)
                        $value = $array;
                }
                #*/

                ## return view with form element
                return View::make($mod_tpl.$tpl, compact('name', 'value', 'params'));
    	    },
            ## Processing results closure
            function($params) use ($mod_tpl, $class) {

                #Helper::dd($params);

                $module  = isset($params['module']) ? $params['module'] : false;
                $unit_id = isset($params['unit_id']) ? $params['unit_id'] : false;
                $data    = isset($params['data']) ? $params['data'] : false;
                $locale  = isset($params['locale']) ? $params['locale'] : NULL;

                if (!$module || !$unit_id)
                    return false;

                #Helper::dd($data);
                #$data['module'] = $module;
                #$data['unit_id'] = $unit_id;
                #Helper::dd($data);

                $seo = Seo::firstOrCreate(array('module' => $module, 'unit_id' => $unit_id, 'language' => $locale));
                $seo->update($data);

                return $seo;
            }
        );
    }
    
    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
        return array(
        	'edit'   => 'Работа с поисковой оптимизацией',
        );
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        return array(
        	'name' => self::$name,
        	'group' => self::$group,
        	'title' => 'SEO',
            'visible' => 1,
        );
    }

    ## Menu elements of the module
    public static function returnMenu() {
        /*
        return array(
            array(
            	'title' => 'Галереи',
                'link' => self::$group,
                'class' => 'fa-picture-o',
                'permit' => 'view',
            ),
        );
        */
    }

    /****************************************************************************/
    
	public function __construct(){
        ##
	}
	
}


