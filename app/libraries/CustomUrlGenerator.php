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

}
