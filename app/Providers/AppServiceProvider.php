<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	$nowEnv = config('app.env');
    	if ($nowEnv == 'dev' || $nowEnv == 'test') {
		    view()->share('SITE_URL', config('app.dev_url'));
	    } else {
		    view()->share('SITE_URL', config('app.product_url'));
	    }

	    view()->share('SITE_NO_HTTPS', config('app.no_http_url'));
	    view()->share('BK_VERSION', config('app.bk_version'));
    }
}
