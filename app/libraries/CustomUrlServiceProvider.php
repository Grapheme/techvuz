<?php namespace Illuminate\Custom;

##
## See \vendor\laravel\framework\src\Illuminate\Routing\RoutingServiceProvider.php
##

use Illuminate\Support\ServiceProvider;

class CustomUrlServiceProvider extends ServiceProvider {

    public function register() {

        ## Wrong Way
        #$this->app->bind('custom_url_generator', function () {
        #    return new \Illuminate\Routing\CustomUrlGenerator;
        #});

        ## Right way
        $this->registerUrlGenerator();

    }

	protected function registerUrlGenerator()
	{
		$this->app['custom_url_generator'] = $this->app->share(function($app)
		{
			// The URL generator needs the route collection that exists on the router.
			// Keep in mind this is an object, so we're passing by references here
			// and all the registered routes will be available to the generator.
			$routes = $app['router']->getRoutes();

			return new \Illuminate\Routing\CustomUrlGenerator($routes, $app->rebinding('request', function($app, $request)
			{
				$app['custom_url_generator']->setRequest($request);
			}));
		});
	}

}