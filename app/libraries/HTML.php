<?php
/**
 * Expansion of HTML facade
 *
 * @author Alexander Zelensky
 * See: http://laravel.ru/articles/odd_bod/extending-request-and-response
 */

namespace Egg\Facades;

use Illuminate\Support\Facades\HTML as BaseHTML;
use Illuminate\Html\HtmlBuilder as HtmlBuilder; 

class HTML extends BaseHTML {

	/**
	 * Generate a link to a CSS file with date of last modification
	 *
	 * @param  string  $url
	 * @param  array   $attributes
	 * @param  bool    $secure
	 * @return string
	 */
	public static function stylemod($url, $attributes = array(), $secure = null)
	{

        $HtmlBuilder = new HtmlBuilder;

		$defaults = array('media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet');

		$attributes = $attributes + $defaults;

        $filemtime = @filemtime(@dirname(@$_SERVER['SCRIPT_FILENAME'])."/".$url);

		$attributes['href'] = asset($url, $secure) . ($filemtime > 0 ? "?".$filemtime : "");

        #print_r( filemtime(dirname($_SERVER['SCRIPT_FILENAME'])."/".$url) ); die;
        #print_r($url); die;
        #print_r($attributes); die;

		return '<link'.$HtmlBuilder->attributes($attributes).'>'.PHP_EOL;
	}


	/**
	 * Generate a link to a JavaScript file.
	 *
	 * @param  string  $url
	 * @param  array   $attributes
	 * @param  bool    $secure
	 * @return string
	 */
	public static function scriptmod($url, $attributes = array(), $secure = null)
	{
        $HtmlBuilder = new HtmlBuilder;

        $filemtime = @filemtime(@dirname(@$_SERVER['SCRIPT_FILENAME'])."/".$url);

		$attributes['src'] = asset($url, $secure) . ($filemtime > 0 ? "?".$filemtime : "");

		return '<script'.$HtmlBuilder->attributes($attributes).'></script>'.PHP_EOL;
	}

}