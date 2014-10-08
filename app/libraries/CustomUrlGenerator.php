<?php namespace Illuminate\Routing;

##
## See \vendor\laravel\framework\src\Illuminate\Routing\UrlGenerator.php
##

use Illuminate\Routing\UrlGenerator;

class CustomUrlGenerator extends UrlGenerator {


	##
    ## Custom URL::route() method
    ##
	public function route($name, $parameters = array(), $absolute = true, $route = null)
	{
        ##
        ## Call url link modifier closure
        ##
        if (@is_callable($this->url_modifiers[$name])) {
            #\Helper::dd($parameters);
            $this->url_modifiers[$name]($parameters);
        }

        ##
        ## Call original URL::route() with 100% right $parameters
        ##
        return parent::route($name, $parameters, $absolute, $route);
	}


    /**
     * Get the URL to a controller action.
     *
     * @param  string  $action
     * @param  mixed   $parameters
     * @param  bool    $absolute
     * @return string
     */
    ##
    ## Custom URL::action() method
    ##
    public function action($action, $parameters = array(), $absolute = true)
    {

        #\Helper::dd(rand(9999999,99999999));
        #\Helper::dd($parameters);

        ##
        ## Call url link modifier closure
        ##
        if (isset($action) && $action != '' && isset($this->url_modifiers[$action]) && @is_callable($this->url_modifiers[$action])) {
            #\Helper::dd($parameters);
            $this->url_modifiers[$action]($parameters);
        }

        return parent::route($action, $parameters, $absolute, $this->routes->getByAction($action));
        #return $this->route($action, $parameters, $absolute, $this->routes->getByAction($action));
    }


    ##
    ## Add url link modifier closure
    ##
    public function add_url_modifier($route_name = false, $closure) {

        if (!is_string($route_name) || !is_callable($closure))
            return false;

        #\Helper::dd($route_name);

        if (!@$this->url_modifiers || !@is_array($this->url_modifiers))
            $this->url_modifiers = array();

        $this->url_modifiers[$route_name] = $closure;
    }


    public function get_modified_parameters($route_name, $params = array()) {
        if (isset($route_name) && $route_name != '' && isset($this->url_modifiers[$route_name]) && @is_callable($this->url_modifiers[$route_name])) {
            #\Helper::d('=== START URL::get_modified_parameters() ===');
            #\Helper::d($route_name);
            #\Helper::d($params);
            $this->url_modifiers[$route_name]($params);
            #\Helper::d($params);
            #\Helper::d('=== END URL::get_modified_parameters() ===');
            return $params;
        }

    }
}
