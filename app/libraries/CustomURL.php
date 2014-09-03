<?php namespace Illuminate\Support\Facades;

##
## See \vendor\laravel\framework\src\Illuminate\Support\Facades\\URL.php
##

use Illuminate\Support\Facades\URL as BaseURL;

/**
 * @see \Illuminate\Routing\UrlGenerator
 */
class CustomURL extends BaseURL {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'custom_url_generator'; }
}

