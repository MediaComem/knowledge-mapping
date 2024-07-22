<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //test if local environment
        if(App::environment('local')) {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
