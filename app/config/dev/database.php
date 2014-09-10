<?php

return array(

	'fetch' => PDO::FETCH_CLASS,
	'default' => 'mysql',
	'connections' => array(
		'sqlite' => array(
			'driver'   => 'sqlite',
			'database' => __DIR__.'/../database/production.sqlite',
			'prefix'   => '',
		),
		'mysql' => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'videogid',
			'username'  => 'root',
			'password'  => 'perl03',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),
		'pgsql' => array(
			'driver'   => 'pgsql',
			'host'     => 'localhost',
			'database' => '',
			'username' => 'root',
			'password' => '',
			'charset'  => 'utf8',
			'prefix'   => '',
			'schema'   => 'public',
		),
		'sqlsrv' => array(
			'driver'   => 'sqlsrv',
			'host'     => 'localhost',
			'database' => '',
			'username' => 'root',
			'password' => '',
			'prefix'   => '',
		),
	),
	'migrations' => 'migrations',
	'redis' => array(
		'cluster' => false,
		'default' => array(
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 0,
		),
	),
);