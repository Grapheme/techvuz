<?php
##
## Custom Request object initialization
## http://laravel.ru/articles/odd_bod/extending-request-and-response#наследование_класса_phprequest-12
##
########################################################################
$app = new Illuminate\Foundation\Application;
########################################################################
//$request = Fideloper\Example\Http\Request::createFromGlobals();
//$app = new Illuminate\Foundation\Application( $request );
########################################################################

$env = $app->detectEnvironment(array(
	'vkharseev' => array('DNS'),
	'artem' => array('MacBook-Pro-Tommy.local'),
    'kd' => array('DobriyMac.local'),
	'server1.grapheme.ru' => array('www.grapheme.ru'),
	'server2.grapheme.ru' => array('grapheme'),
	'tehvuz' => array('tehvuz'),
	'kirill' => array('Mac-mini-Admin.local'),
	'az' => array('Acer_5742G'),
));
$app->bindInstallPaths(require __DIR__.'/paths.php');
$framework = $app['path.base'].'/vendor/laravel/framework/src';
require $framework.'/Illuminate/Foundation/start.php';
return $app;