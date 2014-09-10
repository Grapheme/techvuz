<?php

return array(

	'default' => 'sync',
	'connections' => array(
		'sync' => array(
			'driver' => 'sync',
		),
		'beanstalkd' => array(
			'driver' => 'beanstalkd',
			'host'   => 'localhost',
			'queue'  => 'default',
		),
		'sqs' => array(
			'driver' => 'sqs',
			'key'    => 'your-public-key',
			'secret' => 'your-secret-key',
			'queue'  => 'your-queue-url',
			'region' => 'us-east-1',
		),
		'iron' => array(
			'driver'  => 'iron',
			'project' => 'your-project-id',
			'token'   => 'your-token',
			'queue'   => 'your-queue-name',
		),
		'redis' => array(
			'driver' => 'redis',
			'queue'  => 'default',
		),
	),
	'failed' => array(
		'database' => 'mysql', 'table' => 'failed_jobs',
	),
);
