<?php

return array(

	'default' => 'production',
	'connections' => array(
		'production' => array(
			'host'      => '',
			'username'  => '',
			'password'  => '',
			'key'       => '',
			'keyphrase' => '',
			'root'      => '/var/www',
		),
	),
	'groups' => array(
		'web' => array('production')
	),
);