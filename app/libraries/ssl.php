<?php

class ssl {

	public static function is() {
		
	   	if ( isset($_SERVER['HTTPS']) ) {
	       	if ( 'on' == strtolower($_SERVER['HTTPS']) )
	           	return true;
	       	if ( '1' == $_SERVER['HTTPS'] )
	           	return true;
	   	} elseif ( isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ) {
	    	return true;
		}
		return false;
	}
}