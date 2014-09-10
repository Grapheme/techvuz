<?php

return array(
	'driver' => 'database',
	'lifetime' => 120,
	'expire_on_close' => false,
	'files' => storage_path().'/sessions',
	'connection' => null,
	'table' => 'sessions',
	'lottery' => array(2, 100),
	'cookie' => 'site_session',
	'path' => '/',
	'domain' => null,
	'secure' => false,
);